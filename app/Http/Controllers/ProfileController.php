<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Show the profile edit form
    public function edit()
    {
        // Pass the current user data to the view
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }
// Update the user profile
    public function update(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);
        

        $user = Auth::user();

        // Update user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the user
        $user->save();

        // Redirect to the edit page with a success message
        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully!');
    }
}
