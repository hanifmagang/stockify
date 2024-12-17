<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    

    function tampil(){
        $categories = Category::paginate(20); 
        return view('product.category.tampil', compact('categories')); 
    }

    function tambah(){
        return view('product.category.tambah');
    }
    public function submit(Request $request)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menambahkan category baru', 
        ]);
        
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|integer',
            'description' => 'nullable|string',
        ]);


        Category::create([
            'name' => $request->name,
            'description' => $request->input('description'),

        ]);
        

        return redirect()->route('product.category.tampil')->with('success', 'Category created successfully.'); 
    }

    public function edit($id){
        $categories = Category::find($id);
        return view('product.category.edit', compact('categories'));
    }
    public function update(Request$request, $id){
        $categories = Category::find($id);
        $oldData = $categories->replicate();

        $categories->name = $request->name;
        $categories->description = $request->description;
        $categories->update();

        $changes = [];
        if ($oldData->name !== $categories->name) {
            $changes[] = 'nama';
        }
        if ($oldData->description !== $categories->description) {
            $changes[] = 'deskripsi';
        }

        if (!empty($changes)) {
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => 'User telah mengubah ' . implode(', ', $changes) . ' pada category ' . $categories->name, 
            ]);
        }

        return redirect()->route('product.category.tampil');
    }

    function delete($id){
        $categories = Category::find($id);
        $categories -> delete();
        
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menghapus data category ' .$categories->name,
        ]);
        
        return redirect()->route('product.category.tampil');
    }
}
