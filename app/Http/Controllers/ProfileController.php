<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255|unique:users,name,' . $user->id,
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'photo'    => 'nullable|image|max:2048',
        ], [
            'name.unique' => 'Lietotājvārds jau ir aizņemts.',
            'email.unique' => 'E-pasta adrese jau ir reģistrēta.',
            'password.confirmed' => 'Paroles nesakrīt.',
            'password.min' => 'Parolei jābūt vismaz :min rakstzīmes garai.',
            'password.*' => 'Parolei jābūt vismaz 8 rakstzīmēm, ar lielajiem un mazajiem burtiem, skaitļiem un speciālajām rakstzīmēm.',
            'photo.image' => 'Augšupielādētajam failam jābūt attēlam.',
            'photo.max' => 'Attēla izmērs nedrīkst pārsniegt 2MB.',
        ]);

        $user->name  = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && $user->photo !== 'public/img/default.jpg') {
                Storage::delete($user->photo);
            }

            $path = $request->file('photo')->store('profile', 'public');
            $user->photo = $path;
        }

        if (!$user->photo) {
            $user->photo = 'public/img/default.jpg';
        }

        $user->save();

        return redirect()->route('profile.edit')
                         ->with('status', 'Profils veiksmīgi atjaunināts!');
    }
}
