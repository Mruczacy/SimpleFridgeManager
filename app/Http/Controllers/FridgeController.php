<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Http\Requests\StoreFridgeRequest;
use App\Http\Requests\UpdateFridgeRequest;
use App\Http\Requests\IsFridgeOwnerRequest;
use App\Http\Requests\IsFridgeUserRequest;
use App\Http\Requests\IsPermittedToManageRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class FridgeController extends Controller
{
    public function index(): View
    {
        return view('fridges.index', [
            'fridges' => Fridge::with('products.category')->get()
        ]);
    }

    public function indexOwn(Request $request): View
    {
        $fridges = $request->user()->fridges()->with('products.category')->get();
        return view('fridges.index', [
            'fridges' => $fridges
        ]);
    }

    public function create(): View
    {
        return view('fridges.create');
    }

    public function store(StoreFridgeRequest $request): RedirectResponse
    {
        $fridge = Fridge::create($request->validated() + ['owner_id' => $request->user()->id]);
        $request->user()->fridges()->attach($fridge->id, ['is_manager' => 1]);
        return redirect()->route('myfridges.indexOwn');
    }

    public function show(Fridge $fridge): View
    {
        return view('fridges.show', [
            'fridge' => $fridge
        ]);
    }

    public function showOwn(IsFridgeUserRequest $request, Fridge $fridge): View
    {
        return view('fridges.show', [
            'fridge' => $fridge
        ]);
    }

    public function edit(Fridge $fridge): View
    {
        return view('fridges.edit', [
            'fridge' => $fridge
        ]);
    }

    public function editOwn(IsPermittedToManageRequest $request, Fridge $fridge): View
    {
        return view('fridges.edit', [
            'fridge' => $fridge
        ]);
    }

    public function update(UpdateFridgeRequest $request, Fridge $fridge): RedirectResponse
    {
        $fridge->update($request->validated());
        return redirect()->route('fridges.index');
    }

    public function updateOwn(UpdateFridgeRequest $request, Fridge $fridge): RedirectResponse
    {
        $fridge->update($request->validated());
        return redirect()->route('myfridges.indexOwn');
    }

    public function destroy(Fridge $fridge): RedirectResponse
    {
        $fridge->delete();
        return redirect()->route('fridges.index');
    }

    public function destroyOwn(IsFridgeOwnerRequest $request, Fridge $fridge): RedirectResponse
    {
        $fridge->delete();
        return redirect()->route('myfridges.indexOwn');
    }
}
