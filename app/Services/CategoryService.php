<?php

namespace App\Services;

use App\Models\Category;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Exception;

class CategoryService
{
    /**
     * Get datatable data for Tables
     */
    public function getDatatableData(): JsonResponse
    {
        try {
            $categorys = Category::all();
            
            return DataTables::of($categorys)
            ->addColumn('no', function ($row) {
                static $counter = 0;
                return ++$counter;
            })
            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'editRoute'   => 'admin.category.edit',
                    'deleteRoute' => 'admin.category.destroy',
                    'id'          => $row->id,
                    'showDetail'  => false,

                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    public function getAll()
    {
        $categorys = Category::all();
        return $categorys;
    }
    /**
     * Get Category by ID
     */
    public function getById(int $id): mixed
    {
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return ResponseFormatter::notFound("Kategori dengan ID $id tidak ditemukan.");
            }

            return $category;
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    /**
     * Create new Category
     */
    public function create(array $data): JsonResponse
    {
        try {

            $category = Category::create($data);

            return response()->json([
                'message' => 'Category created successfully!',
                'data' => $category
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Category created failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing Category
     */
    public function update(int $id, array $data): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($data);

            return response()->json([
                'message' => 'Category updated successfully!',
                'data' => $category
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Category update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Category
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Category not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}