<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateProductCategoryRequest;

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

    public function store(ValidateProductCategoryRequest $request)
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

    public function update(ValidateProductCategoryRequest $request, ProductCategory $category)
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
