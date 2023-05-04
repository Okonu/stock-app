<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function create()
    {
        return view('users.create');
    }
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
        ]);

        $user = new User();
        $user->name = $validateData['name'];
        $user->email = $validateData['email'];
        $user->password = Hash::make($validateData['password']);
        $user->is_admin = $validateData['is_admin'];
        $user->save();

        return redirect('/users/')->with('success', 'User created successfully ');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if ($validatedData['password']) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->is_admin = isset($validatedData['is_admin']);
        $user->save();

        return redirect('/users')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/users')->with('success', 'User deleted successfully!');
    }
}
