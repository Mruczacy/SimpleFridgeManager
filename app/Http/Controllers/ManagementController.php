<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ManagementController extends Controller {

    public function attachUserToFridge(Fridge $fridge, User $user, Request $request)
    {
        if($fridge->owners()->contains('id', $user->id)) 
        {
            $request->validate([
                'is_owner' => 'required|numeric|min:0|max:1',
            ]);
            $fridge->users()->attach($user->id, ['is_owner' => $request->is_owner]);
            return redirect()->route('fridges.index');
        }
    }

    public function detachUserFromFridge(Fridge $fridge, User $user, Request $request)
    {
        if($fridge->owners()->contains('id', $user->id)) 
        {
            $fridge->users()->detach($user->id);
            return redirect()->route('fridges.index');
        }
    }

}

?>
