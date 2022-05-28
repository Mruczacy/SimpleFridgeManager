<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\UserController;
use App\Http\Requests\DetachUserFromFridgeRequest;
use App\Http\Requests\IsFridgeOwnerRequest;
use App\Http\Requests\IsFridgeUserRequest;
use App\Http\Requests\UserCandidateRequest;
use App\Http\Requests\UserRankCandidateRequest;
use App\Http\Requests\OwnerCandidateRequest;
use App\Http\Requests\ResignUserFromFridgeRequest;
use Illuminate\Http\Request;

class ManagementController extends Controller {

    public function showAManageForm(IsFridgeOwnerRequest $request,Fridge $fridge) {
        return view('management.manage', [
            'fridge' => $fridge,
            'users' => $fridge->users()->get(),
        ]);
    }

    public function showAMoveForm(Product $product, Fridge $fridge) {
            return view('management.moveproduct', [
                'fridges' => Fridge::all(),
                'product' => $product,
                'def_fridge' => $fridge,
            ]);
    }

    public function showAMoveFormOwn(IsFridgeUserRequest $request,Product $product, Fridge $fridge) {
        return view('management.moveproduct', [
            'fridges' => $request->user()->fridges,
            'def_fridge' => $fridge,
            'product' => $product,
        ]);
    }

    public function attachUserToFridge(Fridge $fridge, UserCandidateRequest $request)
    {
        $validated=$request->validated();
        $fridge->users()->attach($validated['user_id'], ['is_manager' => $validated['is_manager']]);
        return redirect()->route('myfridges.indexOwn');
    }

    public function detachUserFromFridge(DetachUserFromFridgeRequest $request, Fridge $fridge, User $user)
    {
        $fridge->users()->detach($user->id);
        return redirect()->route('myfridges.indexOwn');
    }

    public function resignFromFridge(ResignUserFromFridgeRequest $request, Fridge $fridge)
    {
        $fridge->users()->detach($request->user()->id);
        return redirect()->route('myfridges.indexOwn');
    }

    public function transferOwnership(Fridge $fridge, OwnerCandidateRequest $request){
        $fridge->update($request->validated());
        return redirect()->route('myfridges.indexOwn');
    }

    public function updateUserRank(Fridge $fridge, UserRankCandidateRequest $request)
    {
        $validated=$request->validated();
        $fridge->users()->updateExistingPivot($validated['user_id'], ['is_manager' => $validated['is_manager']]);
        return redirect()->route('myfridges.indexOwn');
    }

}

?>
