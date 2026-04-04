<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Services\CategoryService;
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
        return view('cashier.menu.index');
    }
    /**
     * Get datatable data
     */
    public function tableMenu(): JsonResponse
    {
        return $this->menuService->getDatatableDataCashier();
    }
    public function all()
    {
        return $this->menuService->all();
    }

    public function get(int $id)
    {
        return $this->menuService->getById($id);
    }

    public function toggleStatus(int $id): JsonResponse
    {
        return $this->menuService->toggleStatus($id);
    }
}
