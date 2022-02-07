<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'expiration_date' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->expiration_date = $request->expiration_date;
        $product->save();

        return redirect()->route('fridges.index');
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
        if($product->fridge()->users()->contains('id', Auth::user()->id)) {
            return view('products.edit', ['product' =>$product]);
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
            'expiration_date' => 'required'
        ]);

        $product->name = $request->name;
        $product->expiration_date = $request->expiration_date;
        $product->save();

        return redirect()->route('fridges.index');

    }
    public function updateOwn(Request $request, Product $product)
    {
        if($product->fridge()->users()->contains('id', Auth::user()->id)){
            $request->validate([
                'name' => 'required',
                'expiration_date' => 'required'
            ]);

            $product->name = $request->name;
            $product->expiration_date = $request->expiration_date;
            $product->save();

            return redirect()->route('fridges.index');
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

        return redirect()->route('fridges.index');
    }

    public function destroyOwn(Product $product)
    {
        if($product->fridge()->users()->contains('id', Auth::user()->id)){
            $product->delete();

            return redirect()->route('fridges.index');
        }
    }
}
