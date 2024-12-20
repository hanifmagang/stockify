<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    function tampil(){
        $supplier = Supplier::paginate(20); 
        return view('supplier.tampil', compact('supplier')); 
    }

    function tambah(){
        return view('supplier.tambah');
    }

    function submit(Request $request){
        
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menambahkan data supplier baru', 
        ]);
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:supplier',
            'phone' => 'required|string|max:15|unique:supplier',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }

        
        // dd($validatedData);

        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('supplier.tampil')->with('success', 'Supplier created successfully.'); 
    }

    
    function edit($id){
        $supplier = Supplier::find($id);
        return view('supplier.edit', compact('supplier'));
    }
    function update(Request $request, $id){
        $supplier = Supplier::find($id);
        $oldData = $supplier->replicate();

        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->update();

        $changes = [];
        if ($oldData->name !== $supplier->name) {
            $changes[] = 'nama';
        }
        if ($oldData->address !== $supplier->address) {
            $changes[] = 'alamat';
        }
        if ($oldData->email !== $supplier->email) {
            $changes[] = 'email';
        }
        if ($oldData->phone !== $supplier->phone) {
            $changes[] = 'nomer hp';
        }

        if (count($changes) == 1 && $changes[0] == 'nama') {
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => 'User telah mengubah nama supplier dari ' . $oldData->name . ' menjadi ' . $supplier->name, 
            ]);
        } else if (in_array('nama', $changes)) {
            $otherChanges = array_filter($changes, function($change) { return $change !== 'nama'; });
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => 'User telah mengubah nama supplier ' . $oldData->name . ' menjadi ' . $supplier->name . ', serta mengubah ' . implode(', ', $otherChanges) . '',
            ]);
        } else {
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => 'User telah mengubah ' . implode(', ', $changes) . ' pada supplier ' . $supplier->name, 
            ]);
        }

        return redirect()->route('supplier.tampil');
    }
    function delete($id){
        $supplier = Supplier::find($id);
        $supplier -> delete();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menghapus data supplier ' .$supplier->name, 
        ]);

        return redirect()->route('supplier.tampil');
    }
}
