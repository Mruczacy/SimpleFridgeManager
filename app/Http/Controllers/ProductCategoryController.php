<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;

use App\Http\Requests\ProductCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{

    public function index(): View
    {
        return view('products.categories.index', [
            'categories' => ProductCategory::paginate(25)
        ]);
    }

    public function create(): View
    {
        return view('products.categories.create');
    }

    public function store(ProductCategoryRequest $request): RedirectResponse
    {
        ProductCategory::create($request->validated())->save();

        return redirect()->route('categories.index');
    }

    public function edit(ProductCategory $category): View
    {
        return view('products.categories.edit', [
            'category' => $category
        ]);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $category): RedirectResponse
    {
        $category->update($request->validated());
        return redirect()->route('myfridges.indexOwn');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('myfridges.indexOwn');
    }
}
