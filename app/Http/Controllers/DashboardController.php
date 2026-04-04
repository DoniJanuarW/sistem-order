<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Services\MenuService;
use App\Services\CategoryService;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $menuService, $categoryService, $dashboardService;

    public function __construct(MenuService $menuService, CategoryService $categoryService,  DashboardService $dashboardService)
    {
        $this->menuService = $menuService;
        $this->categoryService = $categoryService;
        $this->dashboardService = $dashboardService;
    }

    public function home(Request $request)
    {
        $menus = $this->menuService->getAllOrderByStatus();
        $categories = $this->categoryService->getAll();
        return view('home', compact(['menus', 'categories']));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $dateParam = $request->input('date'); 
            $data = $this->dashboardService->getAdminStats($dateParam);
            $data['currentDate'] = $data['dateParam'];
            
            return view('dashboard.admin', $data);

        }elseif($user->role === 'cashier'){
            $data = $this->dashboardService->getCashierStats();
            return view('dashboard.cashier', $data);

        }elseif ($user->role === 'customer') {
            $menus = $this->menuService->getAllOrderByStatus();
            $categories = $this->categoryService->getAll();
            return view('dashboard.customer', compact(['menus', 'categories']));

        }else{
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
    }

    public function exportMaster(Request $request)
    {
        $dateParam = $request->input('date');
        return $this->dashboardService->exportAdminStats($dateParam);
    }
}
