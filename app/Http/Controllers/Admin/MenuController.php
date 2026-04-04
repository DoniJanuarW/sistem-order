<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Services\CategoryService;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    protected $menuService;
    protected $categoryService;

    public function __construct(MenuService $menuService, CategoryService $categoryService)
    {
        $this->menuService = $menuService;
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('master.menu.index');
    }
    /**
     * Get datatable data
     */
    public function tableMenu(): JsonResponse
    {
        return $this->menuService->getDatatableDataAdmin();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = $this->categoryService->getAll();
        return view('master.menu.form', ['menu' => null, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request): JsonResponse
    {
        return $this->menuService->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function get(int $id)
    {
        return $this->menuService->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $menu = $this->menuService->getById($id);
        $categories = $this->categoryService->getAll();
        return view('master.menu.form', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, int $id): JsonResponse
    {
        return $this->menuService->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->menuService->delete($id);
    }


    public function toggleStatus(int $id): JsonResponse
    {
        return $this->menuService->toggleStatus($id);
    }
}
