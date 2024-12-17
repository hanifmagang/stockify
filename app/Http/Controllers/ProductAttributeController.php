<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    //
    public function tampil($id){
        $product = Product::findOrFail($id);
        $attributes = ProductAttribute::where('product_id', $id)->with('product')->get();
        return view('product.detail.tampil', compact('product', 'attributes')); 
    }
    public function tambah(){
        $product = Product::all();
        return view('product.detail.tambah', compact('product'));
    }
    public function submit(Request $request){
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',

        ]);

        ProductAttribute::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'value' => $request->value,

        ]);

        return redirect()->route('product.detail.tampil', ['id' => $request->product_id])->with('success', 'Atribut produk berhasil ditambahkan.');
    }
}
