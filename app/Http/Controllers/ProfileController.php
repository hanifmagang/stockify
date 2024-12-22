<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    function tampil(){
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|max:255|confirmed',
            'foto' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($request->file('foto')->isValid()) {
                $path = $request->file('foto')->store('images/profile', 'public');
                $user->foto = basename($path);
            } else {
                return back()->withErrors(['foto' => 'File tidak valid']);
            }
        }


        $user->save();

        return redirect()->route('profile');
    }
}
