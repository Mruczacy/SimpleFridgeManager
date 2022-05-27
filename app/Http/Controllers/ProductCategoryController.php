<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCategoryRequest;

class ProductCategoryController extends Controller
{

    public function index()
    {
        return view('products.categories.index', [
            'categories' => ProductCategory::paginate(25)
        ]);
    }

    public function create()
    {
        return view('products.categories.create');
    }

    public function store(ProductCategoryRequest $request)
    {
        ProductCategory::create($request->validated())->save();

        return redirect()->route('categories.index');
    }

    public function edit(ProductCategory $category)
    {
        return view('products.categories.edit', [
            'category' => $category
        ]);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $category)
    {
        $category->update($request->validated());
        return redirect()->route('myfridges.indexOwn');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()->route('myfridges.indexOwn');
    }
}
