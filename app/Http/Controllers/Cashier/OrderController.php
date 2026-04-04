<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService){}
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('cashier.order.index');
    }

    public function detail($id): View
    {
        $order = $this->orderService->find($id);
        return view('cashier.order.detail', compact('order'));
    }

    public function manual(): View
    {
        return view('cashier.order.create');
    }

    public function all()
    {
        return response()->json($this->orderService->all());
    }

    public function show($id)
    {
        return $this->orderService->find($id);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = $this->orderService->updateStatus($id, $request->status);
            return response()->json([
                'status' => true,
                'message' => 'Status berhasil diperbaharui',
                'data' => $order->status
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        
        try {
            $order = $orderService->createOrder($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Order berhasil dibuat',
                'data' => $order
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
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
