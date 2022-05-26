<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Fridge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\ValidateFridgeIdRequest;
use App\Http\Requests\ValidateProductRequest;

class ProductController extends Controller
{

    public function index()
    {
        return view('products.index', [
            'products' => Product::with('category')->get()
        ]);
    }

    public function create(Fridge $fridge)
    {
        if(Auth::user()->isFridgeUser($fridge)){
            return view('products.create', [
                'def_fridge' => $fridge,
                'categories' => ProductCategory::all(),
                'now' => Carbon::now(),
                'fridges' => Auth::user()->fridges()->get()
            ]);
        } else {
            abort(403, 'Access denied');
        }
    }

    public function store(ValidateProductRequest $request)
    {
        if(Auth::user()->isFridgeUser(Fridge::find($request->fridge_id))) {
            $request->validated();
            $product = new Product();
            $product->name = $request->name;
            $product->expiration_date = $request->expiration_date;
            $product->fridge_id = $request->fridge_id;
            $product->product_category_id = $request->product_category_id ?? null;
            $product->save();
            return redirect()->route('myfridges.showOwn', $request->fridge_id);
        } else {
            abort(403, 'Access denied');
        }
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
    public function editOwn(Product $product)
    {
        if(Auth::user()->isFridgeUser(Fridge::find($product->fridge_id))) {
            return view('products.edit', [
                'product' =>$product,
                'manipulate_date' => Carbon::createFromFormat('Y-m-d', $product->expiration_date),
                'fridges' => Auth::user()->fridges,
                'categories' => ProductCategory::all(),
            ]);
        } else {
            abort(403, 'Access denied');
        }
    }

    public function update(ValidateProductRequest $request, Product $product)
    {
        $request->validated();

        $product->update($request->all());
        $product->save();

        return redirect()->route('myfridges.indexOwn');

    }
    public function updateOwn(ValidateProductRequest $request, Product $product)
    {
        if(Auth::user()->isFridgeUser(Fridge::find($request->fridge_id))){
            $request->validated();
            $product->update($request->all());
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function moveProductBetweenFridges(ValidateFridgeIdRequest $request, Product $product) {
        $request->validated();
        $product->fridge_id = $request->fridge_id;
        $product->save();
        return redirect()->route('fridges.index');
    }

    public function moveProductBetweenFridgesOwn(ValidateFridgeIdRequest $request, Product $product) {
        $request->validated();
        if(Auth::user()->isFridgeUser(Fridge::find($request->fridge_id))) {
            $product->fridge_id = $request->fridge_id;
            $product->save();
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('myfridges.indexOwn');
    }

    public function destroyOwn(Product $product)
    {
        if(Auth::user()->isFridgeUser(Fridge::find($product->fridge_id))){
            $product->delete();
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }
}
