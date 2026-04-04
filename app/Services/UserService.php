<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Exception;

class UserService
{
    /**
     * Get datatable data for users
     */
    public function getDatatableData(): JsonResponse
    {
        try {
            $users = User::where('role', '!=', 'admin')->get();
            
            return DataTables::of($users)
                ->addColumn('no', function ($row) {
                    static $counter = 0;
                    return ++$counter;
                })
                ->addColumn('nama', fn($row) => $row->nama)
                ->addColumn('email', fn($row) => $row->email)
                ->addColumn('phone', fn($row) => $row->phone)
                ->addColumn('full_name', fn($row) => $row->full_name)
                ->addColumn('role', fn($row) => $row->role)
                ->addColumn('action', function ($row) {
                    return view('components.action-buttons', [
                        'editRoute'   => 'admin.user.edit',
                        'deleteRoute' => 'admin.user.destroy',
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

    /**
     * Get user by ID
     */
    public function getById(int $id): mixed
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return ResponseFormatter::notFound("User dengan ID $id tidak ditemukan.");
            }

            return $user;
        } catch (Exception $e) {
            return ResponseFormatter::handleError($e);
        }
    }

    /**
     * Create new user
     */
    public function create(array $data): JsonResponse
    {
        try {
            $data['password'] = bcrypt('password'); // Default password
            
            $user = User::create($data);

            return response()->json([
                'message' => 'User created successfully!',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User created failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing user
     */
    public function update(int $id, array $data): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->update($data);

            return response()->json([
                'message' => 'User updated successfully!',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}