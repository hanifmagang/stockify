<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends Controller
{

    function tampil(){
        $users = User::paginate(20); 
        $roles = User::select('role')->distinct()->pluck('role');
        return view('user.tampil', compact('users', 'roles')); 
    }
    function tambah(){
        return view('user.tambah');
    }
    function submit(Request $request){
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menambahkan data pengguna baru ', 
        ]);
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:8',
            'role' => 'required|in:Admin,Staff Gudang,Manajer Gudang',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }

        

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ]);

        return redirect()->route('user.tampil')->with('success', 'User created successfully.'); 
    }

    function edit($id){
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }
    function update(Request $request, $id){
        $user = User::find($id);
        $oldData = $user->replicate();

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        $user->update();

        $changes = [];
        if ($oldData->email !== $user->email) {
            $changes[] = 'email';
        }
        if ($oldData->role !== $user->role) {
            $changes[] = 'role';
        }

        if ($oldData->name !== $user->name) {
            if (empty($changes)) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'activity' => 'User telah mengubah nama user dari ' . $oldData->name . ' menjadi ' . $user->name,
                ]);
            } else {
                Activity::create([
                    'user_id' => Auth::id(),
                    'activity' => 'User telah mengubah nama user dari ' . $oldData->name . ' menjadi ' . $user->name . ', serta mengubah ' . implode(', ', $changes),
                ]);
            }
        } else {
            if (!empty($changes)) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'activity' => 'User telah mengubah ' . implode(', ', $changes) . ' pada user ' . $user->name,
                ]);
            }
        }

        return redirect()->route('user.tampil');
    }

    function delete($id){
        $users = User::find($id);
        $users -> delete();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menghapus data pengguna dengan nama '. $users->name, 
        ]);
        return redirect()->route('user.tampil');
    }

    
}
