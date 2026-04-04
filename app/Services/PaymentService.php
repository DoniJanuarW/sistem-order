<?php

namespace App\Services;

use App\Models\Payment;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Exception;

class PaymentService
{
    /**
     * Semua transaksi sukses
     */
    public function all(): JsonResponse
    {
        try {
            $payments = Payment::with(['order','order.menu', 'order.table', 'order.customer'])
            ->where('payment_status', 'success')
            ->latest()
            ->get();

            return ResponseFormatter::success('All transactions retrieved', $payments);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    /**
     * Transaksi bulan ini
     */
    public function thisMonth(): JsonResponse
    {
        try {
            $payments = Payment::with('order')
            ->where('payment_status', 'success')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->latest()
            ->get();

            return ResponseFormatter::success( 'This month transactions retrieved', $payments);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

   /**
     * Filter transaksi (Refactored)
     */
   public function filter(?string $from = null, ?string $to = null, ?string $method = null): JsonResponse 
   {
        try {
                    // 1. Base Query (Filter Tanggal & Method saja dulu)
            $baseQuery = Payment::query();

            if ($from) {
                $baseQuery->where('paid_at', '>=', Carbon::parse($from)->startOfDay());
            }
            if ($to) {
                $baseQuery->where('paid_at', '<=', Carbon::parse($to)->endOfDay());
            }
            if ($method && $method !== 'all') {
                $baseQuery->where('method', $method);
            }

                // 2. Hitung Statistik (Menggunakan Base Query)
                // Clone query agar tidak mengganggu query utama
            $statsSuccess = (clone $baseQuery)->where('payment_status', 'success');
            $statsFailed  = (clone $baseQuery)->where('payment_status','failed'); 

            $totalTransactions = $statsSuccess->count();
            $totalIncome       = $statsSuccess->sum('amount');
            $totalCancelled    = $statsFailed->count();

                // Hindari division by zero untuk rata-rata
            $averageIncome = $totalTransactions > 0 ? $totalIncome / $totalTransactions : 0;

                // 3. Ambil Data List (Khusus yang Success saja untuk ditampilkan di tabel)
            $payments = (clone $baseQuery)
            ->with('order')
            ->where('payment_status', 'success')
            ->latest('paid_at')
            ->paginate(10)
            ->appends([
                'from' => $from,
                'to'   => $to,
                'method' => $method
            ]);

                // 4. Return Custom JSON Structure
                // Kita gabungkan data pagination default laravel dengan data stats
            return response()->json(array_merge(
                $payments->toArray(),
                [
                    'stats' => [
                        'total_count' => $totalTransactions,
                        'total_income' => $totalIncome,
                        'average_income' => $averageIncome,
                        'total_cancelled' => $totalCancelled
                    ]
                ]
            ));

        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }
    /**
     * Update status Payment
     */
    public function updateStatus(int $id, string $status)
    {
        $validStatuses = ['pending', 'success', 'failed'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException('Invalid status value');
        }
        $payment = Payment::findOrFail($id);

        $updateData = ['payment_status' => $status];

        if ($status === 'success') {
            $updateData['paid_at'] = now();
        }

        $payment->update($updateData);

        return $payment;


    }
}