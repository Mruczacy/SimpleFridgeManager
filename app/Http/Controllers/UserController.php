<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use App\Http\Requests\UserIsEqualToAuth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateOwnUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        }
        abort(403, 'Access denied');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->route('users.index');
    }

    public function updateOwn(UpdateOwnUserRequest $request, User $user){
        if($user->isEqualToAuth()) {
            $user->update($request->validated());
            return redirect()->route('users.showMyAccount');
        }
        abort(403, 'Access denied');
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
        if($user->isEqualToAuth()) {
            $fridges= $user->managedFridges;
            $user->fridges()->detach();
            foreach($fridges as $fridge){
                $fridge->delete();
            }
            $user->delete();
            return redirect()->route('welcome')->with('success', 'Konto zostało usunięte pomyślnie');
        }
        abort(403, 'Access denied');
    }
}
