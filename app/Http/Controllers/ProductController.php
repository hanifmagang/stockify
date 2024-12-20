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
        $oldData = $product->replicate();

        $product->category_id = $request->category_id;
        $product->supplier_id = $request->supplier_id;
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->purchase_price = $request->purchase_price;
        $product->selling_price = $request->selling_price;
        $product->stockMinimum = $request->stockMinimum;
        // Upload image jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/product', 'public');
            $product->image = $imagePath; // Update image path
        }
        $product->save(); // Simpan perubahan

        // Cek perubahan dan catat aktivitas
        $changes = [];
        if ($oldData->category_id !== $product->category_id) {
            $changes[] = 'category';
        }
        if ($oldData->supplier_id !== $product->supplier_id) {
            $changes[] = 'supplier';
        }
        if ($oldData->name !== $product->name) {
            $changes[] = 'nama';
        }
        if ($oldData->sku !== $product->sku) {
            $changes[] = 'sku';
        }
        if ($oldData->purchase_price !== $product->purchase_price) {
            $changes[] = 'harga beli';
        }
        if ($oldData->selling_price !== $product->selling_price) {
            $changes[] = 'harga jual';
        }
        if ($oldData->description !== $product->description) {
            $changes[] = 'deskripsi';
        }
        if ($oldData->image !== $product->image) {
            $changes[] = 'gambar';
        }
        if ($oldData->stockMinimum !== $product->stockMinimum) {
            $changes[] = 'stock minimum';
        }

        // Hanya catat aktivitas jika ada perubahan
        if (!empty($changes)) {
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => 'User telah mengubah ' . implode(', ', $changes) . ' pada produk ' . $product->name, 
            ]);
        }

        // Update StockOpname jika kategori berubah
        StockOpname::where('product_id', $product->id)->update([
            'category_id' => $product->category_id,
        ]);
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
