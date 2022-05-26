<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\UserController;
use App\Http\Requests\ValidateUserCandidateRequest;
use App\Http\Requests\ValidateOwnerCandidateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ManagementController extends Controller {

    public function showAManageForm(Fridge $fridge) {
        if(Auth::user()->isFridgeOwner($fridge))
        {
            return view('management.manage', [
                'fridge' => $fridge,
                'users' => $fridge->users()->get(),
            ]);
        }
        else
        {
            abort(403, 'Access denied');
        }
    }

    public function showAMoveForm(Product $product, Fridge $fridge) {
            return view('management.moveproduct', [
                'fridges' => Fridge::all(),
                'product' => $product,
                'def_fridge' => $fridge,
            ]);
    }

    public function showAMoveFormOwn(Product $product, Fridge $fridge) {
        if(Auth::user()->isFridgeUser($fridge))
        {
            return view('management.moveproduct', [
                'fridges' => Auth::user()->fridges,
                'def_fridge' => $fridge,
                'product' => $product,
            ]);
        }
        else
        {
            abort(403, 'Access denied');
        }
    }

    public function attachUserToFridge(Fridge $fridge, ValidateUserCandidateRequest $request)
    {

        if(Auth::user()->isFridgeManager($fridge))
        {
            $request->validated();
            $fridge->users()->attach($request->user_id, ['is_manager' => $request->is_manager]);
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function detachUserFromFridge(Fridge $fridge, User $user)
    {
        if(Auth::user()->isFridgeManager($fridge) && $user->isFridgeUserNoOwner($fridge))
        {
            $fridge->users()->detach($user->id);
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function resignFromFridge(Fridge $fridge)
    {
        if(Auth::user()->isFridgeUserNoOwner($fridge))
        {
            $fridge->users()->detach(Auth::id());
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function transferOwnership(Fridge $fridge, ValidateOwnerCandidateRequest $request){
        $request->validated();
        if(Auth::user()->isFridgeOwner($fridge))
        {
            $fridge->update($request->all());
            $fridge->save();
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function updateUserRank(Fridge $fridge, ValidateUserCandidateRequest $request)
    {
        $request->validated();
        if(Auth::user()->isFridgeOwner($fridge))
        {
            $fridge->users()->updateExistingPivot($request->user_id, ['is_manager' => $request->is_manager]);
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

}

?>
