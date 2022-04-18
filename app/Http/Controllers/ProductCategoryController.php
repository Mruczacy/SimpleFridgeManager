<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $productcategory = new ProductCategory();
        $productcategory->name = $request->name;
        $productcategory->save();

        return redirect()->route('categories.index');
    }

    public function edit(ProductCategory $category)
    {
        return view('products.categories.edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $category->name = $request->name;
        $category->save();

        return redirect()->route('myfridges.indexOwn');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()->route('myfridges.indexOwn');
    }
}
