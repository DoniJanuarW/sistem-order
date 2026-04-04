<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Table;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardService
{
    /**
     * Data untuk Dashboard Kasir (Harian)
     */
    public function getCashierStats(): array
    {
        $today = Carbon::today();

        // 1. Statistik Utama
        $stats = [
            'unpaid_orders' => Order::whereHas('payment', function($q) {
                $q->where('payment_status', '!=', 'success');
            })->whereDate('created_at', $today)->count(),

            'active_tables' => Table::where('status', 'occupied')->count(),

            'today_revenue' => Payment::where('payment_status', 'success')
                ->whereDate('paid_at', $today)
                ->sum('amount'),

            'completed_today' => Order::where('status', 'completed')
                ->whereDate('created_at', $today)
                ->count(),
        ];

        $recentOrders = Order::with(['table', 'items', 'payment'])
            ->whereHas('payment', function($q) {
                $q->whereIn('payment_status', ['pending', 'unpaid', 'failed']);
            })
            ->where('status', '!=', 'cancelled')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        $tables = Table::orderBy('table_number')->get();

        return compact('stats', 'recentOrders', 'tables');
    }

    /**
     * Data untuk Dashboard Admin (Bulanan/Filter)
     * Menerima parameter string tanggal (YYYY-MM), bukan Request object
     */
    public function getAdminStats(?string $dateParam = null): array
    {
        $dateParam = $dateParam ?? Carbon::now()->format('Y-m');
        
        $parsedDate = Carbon::createFromFormat('Y-m', $dateParam);
        $startDate  = $parsedDate->copy()->startOfMonth();
        $endDate    = $parsedDate->copy()->endOfMonth();

        $stats = [
            'monthly_revenue' => Payment::where('payment_status', 'success')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('amount'),

            'total_orders'    => Order::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'total_customers' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->distinct('customer_id')
                ->count(),
        ];

        $stats['avg_transaction'] = $stats['total_orders'] > 0 
            ? $stats['monthly_revenue'] / $stats['total_orders'] 
            : 0;

        $dailyRevenues = Payment::where('payment_status', 'success')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartDates = [];
        $chartRevenues = [];
        $daysInMonth = $parsedDate->daysInMonth; 
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateString = $parsedDate->copy()->day($i)->format('Y-m-d');
            
            $chartDates[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ' ' . $parsedDate->translatedFormat('M');
            
            $chartRevenues[] = $dailyRevenues[$dateString] ?? 0;
        }

        // $topMenus = DB::table('order_items')
        //     ->join('menus', 'order_items.menu_id', '=', 'menus.id')
        //     ->join('orders', 'order_items.order_id', '=', 'orders.id')
        //     ->where('orders.status', 'completed')
        //     ->whereBetween('orders.created_at', [$startDate, $endDate])
        //     ->select(
        //         'menus.name', 
        //         'menus.image',
        //         'menus.price',
        //         DB::raw('SUM(order_items.qty) as total_qty'),
        //         DB::raw('SUM(order_items.subtotal) as total_revenue') 
        //     )
        //     ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price')
        //     ->orderByDesc('total_qty')
        //     ->limit(5)
        //     ->get();

        $recentTransactions = Payment::with(['order.table'])
            ->where('payment_status', 'success')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->latest('paid_at')
            ->limit(6)
            ->get();

        return compact(
            'stats', 
            // 'topMenus', 
            'recentTransactions', 
            'dateParam', 
            'chartDates', 
            'chartRevenues'
        );
    }

    /**
     * Generate CSV Export
     * Service mengembalikan StreamedResponse agar controller tinggal return
     */
    public function exportAdminStats(?string $dateParam = null): StreamedResponse
    {
        $dateParam = $dateParam ?? Carbon::now()->format('Y-m');
        
        $startDate = Carbon::createFromFormat('Y-m', $dateParam)->startOfMonth();
        $endDate   = Carbon::createFromFormat('Y-m', $dateParam)->endOfMonth();

        $fileName = 'Laporan_Omset_' . $startDate->format('F_Y') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Tanggal', 'Kode Order', 'Meja', 'Metode Bayar', 'Status', 'Total (Rp)']);

            Payment::with('order.table')
                ->where('payment_status', 'success')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->orderBy('paid_at', 'asc')
                ->chunk(100, function($payments) use ($file) {
                    foreach ($payments as $payment) {
                        fputcsv($file, [
                            Carbon::parse($payment->paid_at)->format('d/m/Y H:i'),
                            $payment->payment_code,
                            $payment->order->table->table_number ?? 'N/A',
                            strtoupper($payment->method),
                            strtoupper($payment->payment_status),
                            $payment->amount
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}