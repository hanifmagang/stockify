<?php

namespace App\Http\Controllers;

use App\Models\Activity;
Use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function tampil(){
        $cat = Category::all();
        $supp = Supplier::all();
        
        $product = Product::with('category','supplier')->get();
        $products = Product::paginate(20);
        
        return view('product.tampil', compact('product', 'products', 'cat', 'supp'));
    }
    
    public function tambah(){

        $cat = Category::all();
        $supp = Supplier::all();
        return view('product.tambah', compact('cat','supp'));
    }

    public function submit(Request $request)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menambahkan produk baru', 
        ]);
        $validatedData = Validator::make($request->all(),[
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product,sku',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'stockMinimum' => 'required|integer|min:0',
        ]);

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/product', 'public');
        } else {
            $imagePath = null;
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->input('description'),
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'image' => $imagePath,
            'stock' => $request->stock,
            'stockMinimum' => $request->stockMinimum,
        ]);
        StockOpname::create([
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'masuk' => 0, // Atur sesuai kebutuhan
            'keluar' => 0, // Atur sesuai kebutuhan
            'stock_akhir' => $product->stock, // Atur sesuai kebutuhan
            'date' => now(), // Atur tanggal sesuai kebutuhan
        ]);
        
        return redirect()->route('product.tampil')->with('success', 'Product created successfully.'); 
    }

    function edit($id){
        $product = Product::find($id);
        $cat = Category::with('category')->findOrFail($id);
        $supp = Supplier::with('supplier')->findOrFail($id);
        return view('product.edit', compact('product', 'cat', 'supp'));
    }
    function update(Request $request, $id){
        $product = Product::find($id);
        $changes = [];

        // Cek perubahan untuk setiap field dan tambahkan ke array changes jika ada perubahan
        if ($product->category_id != $request->category_id) {
            $changes['kategori'] = $request->category_id;
        }
        if ($product->supplier_id != $request->supplier_id) {
            $changes['supplier'] = $request->supplier_id;
        }
        if ($product->name != $request->name) {
            $changes['nama'] = $request->name;
        }
        if ($product->sku != $request->sku) {
            $changes['sku'] = $request->sku;
        }
        if ($product->description != $request->description) {
            $changes['deskripsi'] = $request->description;
        }
        if ($product->purchase_price != $request->purchase_price) {
            $changes['harga beli'] = $request->purchase_price;
        }
        if ($product->selling_price != $request->selling_price) {
            $changes['harga jual'] = $request->selling_price;
        }
        if ($product->stockMinimum != $request->stockMinimum) {
            $changes['stockMinimum'] = $request->stockMinimum;
        }

        // Update product dengan data baru
        $product->update($request->all());

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/product', 'public');
            $product->image = $imagePath; // Update image path
            $changes['gambar'] = 'Image updated';
        }

        // Simpan perubahan
        $product->save();

        // Catat perubahan ke Activity
        if (!empty($changes)) {
            $changeText = 'User telah mengubah ' . implode(', ', array_keys($changes)) . ' pada produk ' . $product->name;
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => $changeText, 
            ]);
        }

        // Update StockOpname jika kategori berubah
        if (array_key_exists('category_id', $changes)) {
            StockOpname::where('product_id', $product->id)->update([
                'category_id' => $product->category_id,
            ]);
        }

        return redirect()->route('product.tampil');
    }

    function delete($id){
        $product = Product::find($id);

         // Hapus entri terkait di StockOpname
        StockOpname::where('product_id', $product->id)->delete();
        $product -> delete();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menghapus produk ' .$product->name, 
        ]);
        return redirect()->route('product.tampil');
    }

    public function exportToExcel()
    {
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export produk', 
        ]);
        return Excel::download(new ProductExport, 'Product' . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan import produk', 
        ]);
        return redirect()->route('product.tampil')->with('success', 'Products imported successfully.');
    }
}
