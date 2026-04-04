<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class ScanController extends Controller
{

    public function scanPage()
    {
        return view('customer.scan');
    }

    public function validateTable(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        $table = Table::where('table_number', $request->qr_data)->first();

        if ($table) {
            return response()->json([
                'status' => 'success',
                'table_number' => $table->table_number,
                'table_id' => $table->id,
                'message' => 'Meja ditemukan!'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Meja tidak terdaftar dalam sistem.'
        ], 404);
    }
}