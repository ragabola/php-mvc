<?php

namespace App\Http\Controllers;

use App\Models\User;
use Setup\Transport\Request;

class HomeController 
{
    public function index()
    {
        $users = User::select('*')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        User::create([
            // your data goes here
        ]);
    }

    public function show(Request $request, $username)
    {
        $user = User::select('*')->where('username', $username)->firstOrFail();
        
        return view('users.show', compact('user'));
    }

    public function edit(Request $request, $username)
    {
        $user = User::select('*')->where('username', $username)->first();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $username)
    {
        User::update([
            // your data goes here
        ])->where('name', $username);

        return navigateTo('/users');
    }

    public function destroy(Request $request, $username)
    {
        User::delete()->where('username', $username);

        return navigateTo('/users');
    }
}