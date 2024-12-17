<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    function tampilRegistrasi(){
        return view('sign-up');
    }

    function submitRegistrasi(Request $request){
        
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:8',
            'role' => 'required|in:Admin,Staff Gudang,Manajer Gudang',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }

        
        // dd($validatedData);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ]);

        return redirect()->route('sign-in.tampil')->with('success', 'User created successfully.'); 
    }

    public function tampilRole(){
        $roles = ['Admin', 'Staff Gudang', 'Manajer Gudang']; 
        return view('sign-up', compact('roles'));
    }

    public function tampilLogin(){
        return view('sign-in');
    }

    public function submitLogin(Request $request){
        $data = $request->only('email', 'password');

        if (Auth::attempt($data)){
            // Activity::create([
            //     'user_id' => Auth::id(),
            //     'activity' => 'User telah login', 
            // ]);

            $request->session()->regenerate();
            return redirect()->route('dashboard.tampil');
        } else {
            return redirect()->back()->with('gagal', 'Email atau Password salah');
        }

        
    }

    public function logout(Request $request) {
        // Activity::create([
        //     'user_id' => Auth::id(),
        //     'activity' => 'User telah logout', 
        // ]);
        Auth::logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('sign-in.tampil')->with('success', 'Anda telah berhasil logout.'); 

        
    }
}
