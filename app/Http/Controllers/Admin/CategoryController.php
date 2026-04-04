<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('master.category.index');
    }
    /**
     * Get datatable data
     */
    public function tablecategory(): JsonResponse
    {
        return $this->categoryService->getDatatableData();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('master.category.form', ['category' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return $this->categoryService->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function get(int $id): JsonResponse
    {
        return $this->categoryService->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $category = $this->categoryService->getById($id);
        return view('master.category.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        return $this->categoryService->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->categoryService->delete($id);
    }
}
