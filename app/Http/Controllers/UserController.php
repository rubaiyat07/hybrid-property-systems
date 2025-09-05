<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    // Show create user form
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'role'=>'required'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show edit form
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user','roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'role'=>'required'
        ]);

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
