<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\PaymentService;

use App\Models\Payment;

class PaymentController extends Controller
{
    protected $paymentService;
    function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return $this->paymentService->all();
    }

    public function thisMonth()
    {
       return $this->paymentService->thisMonth();
   }

    public function filter(Request $request)
    {
        $from   = $request->query('from');
        $to     = $request->query('to');
        $method = $request->query('method');


        return $this->paymentService->filter($from, $to, $method);
    }

    public function updateStatus(Request $request, $id)
    {
       try {
            $payment = $this->paymentService->updateStatus($id, $request->payment_status);
            return response()->json([
                'status' => true,
                'message' => 'Status berhasil diperbaharui',
                'data' => $payment->payment_status
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function export(Request $request)
    {
        $fileName = 'Laporan_Transaksi_' . date('Y-m-d_H-i-s') . '.csv';

        $query = Payment::with(['order', 'cashier_updated_by']); 

        if ($request->filled('from')) {
            $query->whereDate('paid_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('paid_at', '<=', $request->to);
        }
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        $payments = $query->orderBy('paid_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Judul Kolom (Header CSV)
            fputcsv($file, ['ID Pembayaran', 'Tipe Customer', 'Metode', 'Nominal', 'Tanggal Pembayaran', 'Status', 'Kasir']);

            foreach ($payments as $tx) {
                $customerType = $tx->order->customer_id != null ? 'Customer' : 'Cashier';
                $amount = 'Rp ' . number_format($tx->amount, 0, ',', '.');
                $date = date('d-m-Y H:i', strtotime($tx->paid_at));

                fputcsv($file, [
                    $tx->payment_code,
                    $customerType,
                    strtoupper($tx->method),
                    $amount,
                    $date,
                    $tx->payment_status,
                    $tx->cashier_updated_by->full_name ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
