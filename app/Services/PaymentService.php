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
            $baseQuery = Payment::with('order')->whereHas('order', function($q) {
                $q->where('updated_by', auth()->id());
            });

            if ($from) {
                $baseQuery->where('paid_at', '>=', Carbon::parse($from)->startOfDay());
            }
            if ($to) {
                $baseQuery->where('paid_at', '<=', Carbon::parse($to)->endOfDay());
            }
            if ($method && $method !== 'all') {
                if($method === 'transfer'){
                    $baseQuery->where([
                        ['method',"!=" , 'cash'],
                         ['method',"!=" , 'qris']
                         ]);
                }else{
                    $baseQuery->where('method', $method);
                }
            }

            $statsSuccess = (clone $baseQuery)->where('payment_status', 'success');
            $statsFailed  = (clone $baseQuery)->where('payment_status','failed'); 

            $totalTransactions = $statsSuccess->count();
            $totalIncome       = $statsSuccess->sum('amount');
            $totalCancelled    = $statsFailed->count();

            $averageIncome = $totalTransactions > 0 ? $totalIncome / $totalTransactions : 0;

            $payments = (clone $baseQuery)
            ->with('order')
            ->where('payment_status', 'success')
            ->whereHas('order', function($q) {
                $q->where('updated_by', auth()->id());
            })
            ->latest('paid_at')
            ->paginate(10)
            ->appends([
                'from' => $from,
                'to'   => $to,
                'method' => $method
            ]);

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

        $updateData = [
            'payment_status' => $status,
            'updated_by' => auth()->id(),
            ];

        if ($status === 'success') {
            $updateData['paid_at'] = now();
        }
        $payment->update($updateData);
        return $payment;

    }
}