<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\Transaction;


class StockOpnameController extends Controller
{
    public function tampil()
    {
        $product = Product::all();
        $category = Category::all();
        $transactions = Transaction::all();
        $stockOpname = StockOpname::with('product', 'category')->paginate(20);

        foreach ($stockOpname as $opname) {
            $opname['masuk'] = $transactions->where('type', 'Masuk')
                ->where('status', 'Diterima')
                ->where('product_id', $opname->product_id) 
                ->sum('quantity') ?: 0; // Ubah null menjadi 0

            $opname['keluar'] = $transactions->where('type', 'Keluar')
                ->where('status', 'Dikeluarkan')
                ->where('product_id', $opname->product_id) 
                ->sum('quantity') ?: 0; // Ubah null menjadi 0

            $opname['stock_akhir'] = $opname->product->stock + $opname['masuk'] - $opname['keluar'];

            // Update stock_akhir in the database
            $opname->stock_akhir = $opname['stock_akhir'];
            $opname->save(); // Simpan perubahan ke database
        }

        return view('stock.opname.tampil', compact('stockOpname', 'product', 'category'));
    }
    
}
