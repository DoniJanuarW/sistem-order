<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Services\CategoryService;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TransactionController extends Controller
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
        return view('cashier.order.history');
    }

}
