<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\UserController;

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

    public function attachUserToFridge(Fridge $fridge, Request $request)
    {

        if(Auth::user()->isFridgeManager($fridge))
        {
            $request->validate([
                'is_manager' => 'required|numeric|min:0|max:1',
                'user_id' => 'required|numeric|exists:users,id',
            ]);
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

    public function transferOwnership(Fridge $fridge, Request $request){
        $request->validate([
            'owner_id' => 'required|numeric|exists:users,id',
        ]);
        if(Auth::user()->isFridgeOwner($fridge))
        {
            $fridge->update($request->all());
            $fridge->save();
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function updateUserRank(Fridge $fridge, Request $request)
    {
        $request->validate([
            'is_manager' => 'required|numeric|min:0|max:1',
            'user_id' => 'required|numeric|exists:users,id'
        ]);
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
