<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\PaymentService;

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

}
