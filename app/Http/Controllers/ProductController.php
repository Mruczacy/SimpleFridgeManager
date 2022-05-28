<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Fridge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\FridgeIdRequest;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{

    public function index()
    {
        return view('products.index', [
            'products' => Product::with('category')->get()
        ]);
    }

    public function create(Request $request, Fridge $fridge)
    {
        if($request->user()->isFridgeUser($fridge)){
            return view('products.create', [
                'def_fridge' => $fridge,
                'categories' => ProductCategory::all(),
                'now' => now(),
                'fridges' => Auth::user()->fridges()->get()
            ]);
        }
        abort(403, 'Access denied');
    }

    public function store(ProductRequest $request)
    {
        if($request->user()->isFridgeUser(Fridge::findOrFail($request->fridge_id))) {
            $validated=$request->validated();
            Product::create($validated)->save();
            return redirect()->route('myfridges.showOwn', $validated['fridge_id']);
        }
        abort(403, 'Access denied');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' =>$product,
            'manipulate_date' => Carbon::createFromFormat('Y-m-d', $product->expiration_date),
            'fridges' => Fridge::all(),
            'categories' => ProductCategory::all(),
        ]);
    }
    public function editOwn(Request $request, Product $product)
    {
        if($request->user()->isFridgeUser(Fridge::findOrFail($product->fridge_id))) {
            return view('products.edit', [
                'product' => $product,
                'manipulate_date' => Carbon::createFromFormat('Y-m-d', $product->expiration_date),
                'fridges' => $request->user()->fridges()->get(),
                'categories' => ProductCategory::all(),
            ]);
        }
        abort(403, 'Access denied');
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('myfridges.indexOwn');
    }

    public function updateOwn(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('myfridges.indexOwn');
    }

    public function moveProductBetweenFridges(FridgeIdRequest $request, Product $product) {
        $product->update($request->validated());
        return redirect()->route('fridges.index');
    }

    public function moveProductBetweenFridgesOwn(FridgeIdRequest $request, Product $product) {
        if($request->user()->isFridgeUser(Fridge::findOrFail($request->fridge_id))) {
            $product->update($request->validated());
            return redirect()->route('myfridges.indexOwn');
        }
        abort(403, 'Access denied');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('myfridges.indexOwn');
    }

    public function destroyOwn(Request $request, Product $product)
    {
        if($request->user()->isFridgeUser(Fridge::findOrFail($product->fridge_id))){
            $product->delete();
            return redirect()->route('myfridges.indexOwn');
        }
        abort(403, 'Access denied');
    }
}
