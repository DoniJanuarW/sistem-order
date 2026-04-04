<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService){}

    public function history()
    {
        $orders = $this->orderService->getCustomerOrders();
        return view('customer.history', compact('orders'));
    }

    public function checkout(Request $request)
    {
        if($request->order_type === 'dine_in') {
            $request->validate([
                'table_number'   => 'required|numeric',
                'payment_method' => 'required|in:cash,online',
            ]);
        } else {
            $request->validate([
                'payment_method' => 'required|in:cash,online',
            ]);
        }

        try {
            $order = $this->orderService->createOrder([
                'table_number'   => $request->table_number,
                'payment_method' => $request->payment_method,
            ]);

            $snapToken = null;
            if ($request->payment_method === 'online') {
                $snapToken = $this->orderService->generateSnapToken($order);
            }

            return response()->json([
                'status'       => 'success',
                'snap_token'   => $snapToken,
                'redirect_url' => route('customer.order.history') 
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function downloadPdf($id)
    {
        $order = $this->orderService->find($id);
        $pdf = Pdf::loadView('pdf.nota', compact('order'));
        $pdf->setPaper('a5', 'portrait');

        // return $pdf->download('Nota-' . $order->order_code . '.pdf');
        
        // Jika ingin membuka di tab baru (bukan langsung download), 
        return $pdf->stream('Nota-'.$order->order_code.'.pdf');
    }
}