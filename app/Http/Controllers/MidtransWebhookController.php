<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Tangkap semua data dari request (Bisa dari Postman atau Midtrans asli)
        $notif = $request->all();

        // 2. Ambil data yang dibutuhkan
        $transactionStatus = $notif['transaction_status'] ?? null;
        $paymentType = $notif['payment_type'] ?? null;
        $orderIdMidtrans = $notif['order_id'] ?? null;
        $fraudStatus = $notif['fraud_status'] ?? null;
        $signatureKey = $notif['signature_key'] ?? null;
        $grossAmount = $notif['gross_amount'] ?? null;
        $statusCode = $notif['status_code'] ?? null;

        if (!$orderIdMidtrans) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        // ================================================================
        // 3. VALIDASI SIGNATURE (Keamanan Production)
        // ================================================================
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // Cek apakah ini tes dari Postman (Tidak ada signature) atau dari Server Midtrans Asli
        if ($signatureKey) {
            // Jika dari Midtrans asli, kita cocokkan rumus keamanannya
            $mySignature = hash('sha512', $orderIdMidtrans . $statusCode . $grossAmount . $serverKey);
            
            if ($mySignature !== $signatureKey) {
                Log::error('Midtrans Webhook: Signature tidak cocok!');
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        } else {
            // Jika dari Postman, izinkan HANYA jika APP_ENV = local
            if (env('APP_ENV') !== 'local') {
                return response()->json(['message' => 'Signature missing in production'], 403);
            }
        }
        // ================================================================

        // 4. Ekstrak Order Code (Karena format kita: ORD00001-1700000000)
        $orderCode = explode('-', $orderIdMidtrans)[0];

        // 5. Cari Order di Database
        $order = Order::where('order_code', $orderCode)->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::where('order_id', $order->id)->first();

        // 6. Update Status Berdasarkan Respon Midtrans
        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($fraudStatus == 'challenge') {
                    $this->updateStatus($order, $payment, 'pending', 'pending', $notif['transaction_id'], $paymentType);
                } else {
                    $this->updateStatus($order, $payment, 'cooking', 'success', $notif['transaction_id'], $paymentType);
                }
            }
        } else if ($transactionStatus == 'settlement') {
            // PEMBAYARAN SUKSES
            $this->updateStatus($order, $payment, 'cooking', 'success', $notif['transaction_id'] ?? null, $paymentType);
            
        } else if ($transactionStatus == 'pending') {
            // MENUNGGU PEMBAYARAN
            $this->updateStatus($order, $payment, 'pending', 'pending', $notif['transaction_id'] ?? null, $paymentType);
            
        } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // GAGAL / KEDALUWARSA
            $this->updateStatus($order, $payment, 'cancelled', 'expired', $notif['transaction_id'] ?? null, $paymentType);
        }

        return response()->json(['message' => 'Webhook success']);
    }

    private function updateStatus($order, $payment, $orderStatus, $paymentStatus, $midtransId = null, $payType = null)
    {
        $order->update(['status' => $orderStatus]);

        if ($payment) {
            $payment->update([
                'payment_status' => $paymentStatus,
                'midtrans_transaction_id' => $midtransId ?? $payment->midtrans_transaction_id,
                'method' => $payType ?? $payment->method,
                'paid_at' => $paymentStatus === 'success' ? now() : null
            ]);
        }
    }
}