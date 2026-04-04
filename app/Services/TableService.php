<?php

namespace App\Services;

use App\Models\Table;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class TableService
{
    /**
     * Get datatable data for Tables
     */
    public function getDatatableData(): JsonResponse
    {
        try {
            $tables = Table::all();
            
            return DataTables::of($tables)
            ->addColumn('no', function ($row) {
                static $counter = 0;
                return ++$counter;
            })
            ->addColumn('table_number', fn($row) => $row->table_number)
            ->addColumn('status', fn($row) => $row->status)
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'editRoute'   => 'admin.table.edit',
                    'deleteRoute' => 'admin.table.destroy',
                    'id'          => $row->id,
                    'showDetail'  => true,
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }
    /**
    * Get all Tables
    */
    public function all()
    {
        try {
            $tables = Table::all();
            if (!$tables) {
                return ResponseFormatter::notFound("Meja tidak ditemukan.");
            }
            return $tables;
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }


    /**
     * Get Table by ID
     */
    public function getById(int $id): mixed
    {
        try {
            $table = Table::find($id);

            $qrCode = base64_encode(
                QrCode::format('png')
                ->size(300)
                ->generate($table->table_number)
            );



            if (!$table) {
                return ResponseFormatter::notFound("Table dengan ID $id tidak ditemukan.");
            }
            return response()->json([
                'id' => $table->id,
                'table_number' => $table->table_number,
                'qr_code' => $table->qr_code,
                'status' => $table->status,
                'created_at' => $table->created_at,
                'qr_image' => 'data:image/png;base64,' . $qrCode
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    /**
     * Create new Table
     */
    public function create(array $data): JsonResponse
    {
        try {

            $table = Table::create($data);

            return response()->json([
                'message' => 'Table created successfully!',
                'data' => $table
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Table created failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing Table
     */
    public function update(int $id, array $data): JsonResponse
    {
        try {
            $table = Table::findOrFail($id);
            $table->update($data);

            return response()->json([
                'message' => 'Table updated successfully!',
                'data' => $table
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Table update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Table
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $table = Table::findOrFail($id);
            $table->delete();

            return response()->json([
                'message' => 'Table deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Table not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $table = Table::findOrFail($id);

            $table->status = $table->status === 'active'
            ? 'inactive'
            : 'active';

            $table->save();

            return response()->json([
                'message' => 'Table status updated.',
                'status' => $table->status
            ], 200);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

}