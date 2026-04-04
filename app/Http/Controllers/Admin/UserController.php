<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display user index page
     */
    public function index(): View
    {
        return view('master.user.index');
    }

    /**
     * Get datatable data
     */
    public function tableUser(): JsonResponse
    {
        return $this->userService->getDatatableData();
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return view('master.user.form', ['user' => null]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): View
    {
        $user = $this->userService->getById($id);
        return view('master.user.form', compact('user'));
    }

    /**
     * Get user by ID
     */
    public function get(int $id): JsonResponse
    {
        return $this->userService->getById($id);
    }

    /**
     * Store new user
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        return $this->userService->create($request->validated());
    }

    /**
     * Update existing user
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->userService->update($id, $request->validated());
    }

    /**
     * Delete user
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->userService->delete($id);
    }
}