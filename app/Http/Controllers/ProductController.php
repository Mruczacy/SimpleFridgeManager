<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Fridge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index', [
            'products' => Product::paginate(50)
        ]);
    }

    public function indexOwn()
    {
        $user = Auth::user();
        return view('products.index', [
            'fridges' => $user->fridges
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     * Only the owner of the fridge can add products to it.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if(Auth::user()->isFridgeUser($request->fridge_id)) {
            $request->validate([
                'name' => 'required',
                'expiration_date' => 'required',
                'fridge_id' => 'required',
                'product_category_id' => 'required'
            ]);
            $product = new Product();
            $product->name = $request->name;
            $product->expiration_date = $request->expiration_date;
            $product->fridge_id = $request->fridge_id;
            $product->product_category_id = $request->product_category_id;
            $product->save();
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' =>$product]);
    }
    public function editOwn(Product $product)
    {
        if(Auth::user()->isFridgeUser($product->fridge_id)) {
            return view('products.edit', ['product' =>$product]);
        } else {
            abort(403, 'Access denied');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'expiration_date' => 'required',
            'product_category_id' => 'required',
            'fridge_id' => 'required'
        ]);

        $product->update($request->all());
        $product->save();

        return redirect()->route('myfridges.indexOwn');

    }
    public function updateOwn(Request $request, Product $product)
    {
        if(Auth::user()->isFridgeUser($product->fridge_id)){
            $request->validate([
                'name' => 'required',
                'product_category_id' => 'required',
                'fridge_id' => 'required',
                'expiration_date' => 'required'
            ]);
            $product->update($request->all());


            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function moveProductBetweenFridges(Request $request, Product $product) {
        $request->validate([
            'fridge_id' => 'required',
        ]);
        $product->fridge_id = $request->fridge_id;
        $product->save();
        return redirect()->route('fridges.index');
    }

    public function moveProductBetweenFridgesOwn(Request $request, Product $product) {
        $request->validate([
            'fridge_id' => 'required',
        ]);
        if(Auth::user()->isFridgeUser($product->fridge_id)) {
            $product->fridge_id = $request->fridge_id;
            $product->save();
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('myfridges.indexOwn');
    }

    public function destroyOwn(Product $product)
    {
        if(Auth::user()->isFridgeUser($product->fridge_id)){
            $product->delete();

            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }
}
