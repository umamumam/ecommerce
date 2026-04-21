<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $customers = User::where('role', 'user')->orderBy('created_at', 'desc')->get();
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();

        return view('users.index', compact('users', 'customers', 'admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'is_active' => 'required|boolean',
        ]);

        $user->update($request->only('name', 'username', 'email', 'role', 'is_active'));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->profile_photo) {
            Storage::delete('public/' . $user->profile_photo);
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    public function show(User $user)
    {
        return response()->json($user);
    }
}
