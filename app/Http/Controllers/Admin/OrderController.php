<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = $this->orderService->getAdminOrdersQuery($request);
        $orders = $query->paginate(10)->appends($request->all());

        return view('master.order.index', compact('orders'));
    }

    public function export(Request $request)
    {
        $fileName = 'Laporan_Transaksi_' . Carbon::now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $query = $this->orderService->getAdminOrdersQuery($request);

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Waktu Order', 'Kode Order', 'Pelanggan', 'Meja', 'Total', 'Metode Bayar', 'Status Pembayaran', 'Status Order']);

            $query->chunk(100, function($orders) use ($file) {
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->created_at->format('d/m/Y H:i'),
                        $order->order_code,
                        $order->guest_name ?? $order->customer->name ?? 'Umum',
                        $order->table->table_number ?? 'Takeaway',
                        $order->total_amount,
                        strtoupper($order->payment->method ?? '-'),
                        strtoupper($order->payment->payment_status ?? 'Unpaid'),
                        strtoupper($order->status)
                    ]);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}