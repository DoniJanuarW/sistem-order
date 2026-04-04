<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TableService;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

class TableController extends Controller
{
    protected $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('master.table.index');
    }
    /**
     * Get datatable data
     */
    public function tableTable(): JsonResponse
    {
        return $this->tableService->getDatatableData();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('master.table.form', ['table' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTableRequest $request): JsonResponse
    {
        return $this->tableService->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function get(int $id): JsonResponse
    {
        return $this->tableService->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $table = $this->tableService->getById($id);
        return view('master.table.form', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, int $id): JsonResponse
    {
        return $this->tableService->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->tableService->delete($id);
    }

    public function qr($id)
    {
        return QrCode::format('png')
        ->size(300)
        ->generate($this->get($id));
    }

    public function toggleStatus(int $id): JsonResponse
    {
        return $this->tableService->toggleStatus($id);
    }


    public function generateAllQrPdf()
    {
        try {
            $tables = $this->tableService->all();
            $qrData = [];

            foreach ($tables as $table) {
                $content = $table->table_number; 

                $qrCodeImage = QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate($content);

                $qrData[] = [
                    'table_number' => $table->table_number,
                    'qr_image'     => 'data:image/png;base64,' . base64_encode($qrCodeImage)
                ];
            }

            $pdf = Pdf::loadView('pdf.qr_codes', [
                'tables' => $qrData
            ]);

            $pdf->setPaper('a4', 'portrait');

            return $pdf->stream('All_Tables_QR.pdf');

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
