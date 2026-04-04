<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
   public function paymentPage($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('customer_id', Auth::id()) 
                      ->firstOrFail();

        return view('customer.payment', compact('order'));
    }

   public function confirmPayment(Request $request, $orderId)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $order = Order::where('id', $orderId)
                          ->where('customer_id', Auth::id())
                          ->firstOrFail();

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $order->order_code . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('payment_proofs', $filename, 'public');
                $order->payment()->update([
                    'transfer_proof' => $path,
                ]);
                $order->update([
                    'status' => 'pending', 
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bukti pembayaran berhasil diupload. Mohon tunggu verifikasi kasir.'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder($orderId)
    {
        try {
            $order = Order::where('id', $orderId)
                          ->where('customer_id', Auth::id())
                          ->firstOrFail();

            if ($order->status === 'pending') {
                $order->update(['status' => 'cancelled']);
                return redirect()->route('customer.order.history')->with('success', 'Pesanan berhasil dibatalkan.');
            } else {
                return redirect()->route('customer.order.history')->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('customer.order.history')->with('error', 'Pesanan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('customer.order.history')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
