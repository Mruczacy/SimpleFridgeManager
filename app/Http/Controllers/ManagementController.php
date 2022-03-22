<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\UserController;

class ManagementController extends Controller {

    public function showAnAttachForm(Fridge $fridge, User $user) {
        if(Auth::user()->isFridgeOwner($fridge))
        {
            return view('management.attach', compact('fridge', 'user'));
        }
        else
        {
            abort(403, 'Access denied');
        }
    }

    public function attachUserToFridge(Fridge $fridge, User $user, Request $request)
    {

        if(Auth::user()->isFridgeOwner($fridge))
        {
            $request->validate([
                'is_owner' => 'required|numeric|min:0|max:1',
            ]);
            $fridge->users()->attach($user->id, ['is_owner' => $request->is_owner]);
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function detachUserFromFridge(Fridge $fridge, User $user, Request $request)
    {
        if(Auth::user()->isFridgeOwner($fridge))
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
            $user = Auth::user();
            $fridge->users()->detach($user->id);
            $user->fridges()->detach($fridge->id);
            return redirect()->route('myfridges.indexOwn');
        } else {
            abort(403, 'Access denied');
        }
    }

}

?>
