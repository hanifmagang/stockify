<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Product;
use App\Models\Activity;
use App\Models\Category;
use App\Models\StockOpname;
use App\Models\Transaction;
use App\Exports\StockExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanStockController extends Controller
{
    public function tampil(Request $request){
        $product = Product::all();
        $category = Category::all();
        
        // Menyiapkan query untuk transaksi
        $transactions = Transaction::query();

        // Menambahkan filter berdasarkan tanggal
        if ($request->has('start-date') && $request->has('end-date')) {
            $transactions->whereBetween('updated_at', [$request->input('start-date'), $request->input('end-date')]);
        }

        // Menambahkan filter berdasarkan kategori produk
        if ($request->has('category') && $request->input('category') != '') {
            $transactions->whereHas('product', function($query) use ($request) {
                $query->where('category_id', $request->input('category'));
            });
        }

        // Mengurutkan transaksi berdasarkan 'updated_at' untuk mendapatkan transaksi terbaru
        $transactions = $transactions->orderBy('updated_at', 'desc')->paginate(20);


        return view('laporan.stock.tampil', compact('product', 'category', 'transactions'));
    }

    public function exportExcel(Request $request)
    {
        $date = now()->format('Y-m-d');

        $transactions = Transaction::query();

        // Menambahkan filter berdasarkan tanggal
        if ($request->has('start-date') && $request->has('end-date')) {
            $transactions->whereBetween('updated_at', [$request->input('start-date'), $request->input('end-date')]);
        }
        

        // Menambahkan filter berdasarkan kategori produk
        if ($request->has('category') && $request->input('category') != '') {
            $transactions->whereHas('product', function($query) use ($request) {
                $query->where('category_id', $request->input('category'));
            });
        }

        // Mengurutkan transaksi berdasarkan 'updated_at' untuk mendapatkan transaksi terbaru
        $transactions = $transactions->orderBy('updated_at', 'desc')->with('product.category')->get();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export excel laporan stock ' , 
        ]);

        return Excel::download(new StockExport($transactions), 'stock_' . $date . '.xlsx');
    }
    

    public function exportPDF(Request $request)
    {
        
        $request->validate([
            'start-date' => 'nullable|date',
            'end-date' => 'nullable|date|after_or_equal:start-date',
            'category' => 'nullable',
        ]);

        $date = now()->format('Y-m-d');
        $transactions = Transaction::query();
        
        // Menambahkan filter berdasarkan tanggal
        if ($request->has('start-date') && $request->has('end-date')) {
            $transactions->whereBetween('updated_at', [$request->input('start-date'), $request->input('end-date')]);
        }

        // Menambahkan filter berdasarkan kategori produk
        if ($request->has('category') && $request->input('category') != '') {
            $transactions->whereHas('product', function($query) use ($request) {
                $query->where('category_id', $request->input('category'));
            });
        }

        // Mengurutkan transaksi berdasarkan 'updated_at' untuk mendapatkan transaksi terbaru
        $transactions = $transactions->orderBy('updated_at', 'desc')->with('product.category')->get();

        $pdf = PDF::loadView('laporan.stock_pdf', compact('transactions', 'date'));

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export pdf laporan stock ', 
        ]);

        return $pdf->stream('laporan_stock_' . $date . '.pdf');
        
    }
}
