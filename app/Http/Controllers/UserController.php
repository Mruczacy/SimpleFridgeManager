<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Enums\UserRole;

class UserController extends Controller
{

    public function index() : View
    {

        return view('users.index', [
            'users' => User::paginate(5)
        ]);
    }

    public function showMyAccount() : View
    {
        return view('users.myaccount', [
            'user' => Auth::user()
        ]);
    }

    public function edit(User $user) : View
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => UserRole::class
        ]);
    }

    public function editOwn(User $user)
    {
        if($user->isEqualToAuth()) {
            return view('users.edit', [
                'user' => $user
            ]);
        } else {
            abort(403, 'Access denied');
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:rfc',
            'role' => 'required',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->update();

        return redirect()->route('users.index');
    }

    public function updateOwn(Request $request, User $user){
        if($user->isEqualToAuth()){
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email:rfc',

            ]);
            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;
            $user->update();

            return redirect()->route('users.showMyAccount');
        } else {
            abort(403, 'Access denied');
        }
    }

    public function destroy(User $user)
    {
        $fridges= $user->managedFridges;
        $user->fridges()->detach();
        foreach($fridges as $fridge){
            $fridge->delete();
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Konto zostało usunięte pomyślnie');
    }

    public function destroyOwn(User $user){
        if($user->isEqualToAuth()){
            $fridges= $user->managedFridges;
            $user->fridges()->detach();
            foreach($fridges as $fridge){
                $fridge->delete();
            }
            $user->delete();
            return redirect()->route('welcome')->with('success', 'Twoje konto zostało usunięte pomyślnie');
        } else {
            abort(403, 'Access denied');
        }
    }
}
