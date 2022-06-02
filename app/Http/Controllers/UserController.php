<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use App\Http\Requests\IsEqualToAuthRequest;
use App\Http\Requests\UserIsEqualToAuth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateOwnUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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

    public function showMyAccount(Request $request) : View
    {
        return view('users.myaccount', [
            'user' => $request->user()
        ]);
    }

    public function edit(User $user) : View
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => UserRole::class
        ]);
    }

    public function editOwn(IsEqualToAuthRequest $request, User $user): View
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        return redirect()->route('users.index');
    }

    public function updateOwn(UpdateOwnUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        return redirect()->route('users.showMyAccount');
    }

    public function destroy(User $user): RedirectResponse
    {
        $fridges= $user->managedFridges;
        $user->ownFridges()->delete();
        $user->fridges()->detach();
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Konto zostało usunięte pomyślnie');
    }

    public function destroyOwn(IsEqualToAuthRequest $request, User $user): RedirectResponse
    {
        $fridges= $user->managedFridges;
        $user->ownFridges()->delete();
        $user->fridges()->detach();
        $user->delete();
        return redirect()->route('welcome')->with('success', 'Konto zostało usunięte pomyślnie');
    }
}
