<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Enums\UserRole;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) : View
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => UserRole::class
        ]);
    }

    public function editOwn(User $user)
    {
        if(Auth::user()->id == $user->id) {
            return view('users.edit', [
                'user' => $user
            ]);
        } else {
            abort(403, 'Access denied');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
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
        if(Auth::user()->id == $user->id){
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $fridges= $user->ownFridges;
        $user->fridges()->detach();
        foreach($fridges as $fridge){
            $fridge->delete();
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Konto zostało usunięte pomyślnie');
    }

    public function destroyOwn(User $user){
        if(Auth::user()->id == $user->id){
            $fridges= $user->ownFridges;
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
