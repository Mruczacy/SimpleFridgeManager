<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('users.index', [
            'users' => User::paginate(5)
        ]);
    }

    public function showMyAccount(User $user){
        return view('users.myaccount', [
            'user' => $user
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function editOwn(User $user)
    {
        if(Auth::user()->id == $user->id) {
            return view('users.edit', [
                'user' => $user
            ]);
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
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index');
    }

    public function updateOwn(Request $request, User $user){
        if(Auth::user()->id == $user->id){
            $request->validate([
                'name' => 'required',
                'email' => 'required',
            ]);

            $user->update($request->all());

            return redirect()->route('home');
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
        $user->delete();
        return redirect()->route('welcome')->with('success', 'Konto zostało usunięte pomyślnie');
    }

    public function destroyOwn(User $user){
        if(Auth::user()->id == $user->id){
            $user->delete();
            return redirect()->route('welcome')->with('success', 'Twoje konto zostało usunięte pomyślnie');
        }
    }
}
