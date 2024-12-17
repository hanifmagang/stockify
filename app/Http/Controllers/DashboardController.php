<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Activity;
use App\Models\Category;
use App\Models\StockOpname;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function tampil(Request $request){
        
        
        // Ambil parameter tanggal dari request
        $startDate = $request->input('start-date');
        $endDate = $request->input('end-date');

        // Ambil data transaksi dengan filter tanggal jika ada
        $filteredTransactionsQuery = Transaction::when($startDate, function($query) use ($startDate) {
            return $query->where('date', '>=', $startDate);
        })->when($endDate, function($query) use ($endDate) {
            return $query->where('date', '<=', $endDate);
        });

        // Clone query untuk penghitungan total sebelum paginasi
        $allTransactions = (clone $filteredTransactionsQuery)->get();

        // Hitung total transaksi masuk dan keluar dari semua data
        $totalTransIn = $allTransactions->where('type', 'Masuk')->where('status', 'Diterima')->count();
        $totalTransOut = $allTransactions->where('type', 'Keluar')->where('status', 'Dikeluarkan')->count();

        // Lakukan paginasi pada query asli
        $transactions = $filteredTransactionsQuery->orderBy('updated_at', 'desc')->paginate(10);

        $stockOpname = StockOpname::all();
        $product = Product::all();
        $category = Category::all();
        $trans = Transaction::all();
        $categoriesWithCount = Category::withCount('product')->get(); 
        
        $transactions->load('product.category'); // Memuat relasi kategori produk
        $totalProduct = Product::count();
        $activities = Activity::all(); 
        
        // Untuk Manajer Gudang: Filter transaksi hari ini
        if (auth()->user()->role === 'Manajer Gudang') {
            $transactions = Transaction::whereDate('updated_at', Carbon::today())->paginate(10);
        }

        // Untuk Staff Gudang: Filter transaksi dengan status 'Pending'
        if (auth()->user()->role === 'Staff Gudang') {
            $transactions = Transaction::where('status', 'Pending')->paginate(10);
        }

        return view('dashboard', compact('transactions', 'product', 'category', 'stockOpname', 'trans', 'totalProduct', 'totalTransIn', 'totalTransOut',  'categoriesWithCount', 'activities')); // Kirim data ke view

        
    }
}
