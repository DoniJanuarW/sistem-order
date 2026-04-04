<?php

namespace App\Services;

use App\Models\Menu;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class MenuService
{
    /**
     * Get datatable data for Tables
     */
    public function getDatatableDataAdmin(): JsonResponse
    {
        try {
            $menus = Menu::with("category")->get();
            
            return DataTables::of($menus)
            ->addColumn('no', function ($row) {
                static $counter = 0;
                return ++$counter;
            })
            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('price', fn($row) => $row->price)
            ->addColumn('description', fn($row) => $row->description)
            ->addColumn('category', fn($row) => $row->category->name)
            ->addColumn('status', fn($row) => $row->status)
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'editRoute'   => 'admin.menu.edit',
                    'deleteRoute' => 'admin.menu.destroy',
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

    public function getDatatableDataCashier(): JsonResponse
    {
        try {
            $menus = Menu::with("category")->get();
            
            return DataTables::of($menus)
            ->addColumn('no', function ($row) {
                static $counter = 0;
                return ++$counter;
            })
            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('price', fn($row) => $row->price)
            ->addColumn('description', fn($row) => $row->description)
            ->addColumn('category', fn($row) => $row->category->name)
            ->addColumn('status', fn($row) => $row->status)
            ->make(true);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }
    /**
     * Get all Menus
     */

    public function all()
    {
        try {
            $menu = Menu::with('category')->get();
            
            if (!$menu) {
                return ResponseFormatter::notFound("Menu tidak ditemukan.");
            }
            // $menu->image = $menu->image ? asset('storage/' . $menu->image) : null;

            return $menu;
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    public function getAllOrderByStatus()
    {
        $products = Menu::with(['category'])->orderBy('status', 'asc')
            ->orderBy('category_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        return $products;
    }

    /**
     * Get Menu by ID
     */
    public function getById(int $id): mixed
    {
        try {
            $menu = Menu::with('category')->find($id);
            
            if (!$menu) {
                return ResponseFormatter::notFound("Menu dengan ID $id tidak ditemukan.");
            }
            $menu->image = $menu->image ? asset('storage/' . $menu->image) : null;

            return $menu;
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    /**
     * Create new Menu
     */
    public function create(array $data): JsonResponse
    {
        try {
            if (isset($data['image'])) {
                $data['image'] = $this->uploadImage($data['image']);
            }

            $menu = Menu::create($data);

            return response()->json([
                'message' => 'Menu created successfully!',
                'data' => $menu
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Menu created failed.',
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
            $menu = Menu::findOrFail($id);

            if (isset($data['image'])) {
                $this->deleteImage($menu->image);
                $data['image'] = $this->uploadImage($data['image']);
            }

            $menu->update($data);

            return response()->json([
                'message' => 'Menu updated successfully!',
                'data' => $menu
            ], 200);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    /**
     * Delete Menu
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $menu = Menu::findOrFail($id);

            $this->deleteImage($menu->image);
            $menu->delete();

            return response()->json([
                'message' => 'Menu deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Menu not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    private function uploadImage(?UploadedFile $image): ?string
    {
        if (!$image) {
            return null;
        }

        return $image->store('menus', 'public');
    }

    private function deleteImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    public function getForSelect(): JsonResponse
    {
        try {
            $menus = Menu::select('id', 'name')
            ->where('status', 'available')
            ->orderBy('name')
            ->get();

            return response()->json([
                'data' => $menus
            ], 200);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $menu = Menu::findOrFail($id);

            $menu->status = $menu->status === 'available'
            ? 'unavailable'
            : 'available';

            $menu->save();

            return response()->json([
                'message' => 'Menu status updated.',
                'status' => $menu->status
            ], 200);
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }


}