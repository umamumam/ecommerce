<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function address()
    {
        $user = Auth::user();
        return view('account.address', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'username', 'phone', 'gender', 'dob');
        
        // Remove null values if we are doing a partial update (like photo only)
        $data = array_filter($data, function($value) {
            return !is_null($value);
        });

        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string',
        ]);

        $user->update($request->only('province', 'city', 'district', 'postal_code', 'address'));

        return back()->with('success', 'Alamat berhasil diperbarui');
    }
}
