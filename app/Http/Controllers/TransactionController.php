<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Activity;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    function tampil($type){
        $status = ['Pending', 'Diterima', 'Ditolak', 'Dikeluarkan'];
        $transactions = Transaction::with('product','user')
        ->where('type', $type)
        ->paginate(20); 
        $us = User::all();
        $prod = Product::all();
        return view('stock.transaction.tampil', compact('transactions', 'prod', 'us','type', 'status')); 
    }
    function tambah(){
        return view('stock.transaction.tambah', compact('types', 'status'));
    }

    public function submit(Request $request, $type)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah menambahkan transaksi ' . strtolower($type) . ' baru ', 
        ]);
        
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Ambil produk untuk mendapatkan stock awal
        $product = Product::findOrFail($request->product_id);
        $stockAwal = $product->stock; // Misalkan ada kolom 'stock' di tabel product

        // Ambil transaksi terakhir untuk mendapatkan stockSementara
        $lastTransaction = Transaction::where('product_id', $request->product_id)
            ->orderBy('created_at', 'desc')
            ->first();
        $stockSementara = null; // Inisialisasi dengan null
        // Inisialisasi stockSementara
        if (!$lastTransaction) {
            // Jika ini adalah transaksi pertama
            if ($type === 'Masuk' && $request->status === 'Diterima') {
                $stockSementara = $stockAwal + $request->quantity; // Tambah quantity untuk transaksi masuk
            } elseif ($type === 'Keluar' && $request->status === 'Dikeluarkan') {
                if ($stockAwal < $request->quantity) {
                    return response()->json(['error' => 'Stock tidak cukup untuk transaksi ini.'], 400);
                }
                $stockSementara = $stockAwal - $request->quantity; // Kurangi quantity untuk transaksi keluar
            } else {
                $stockSementara = $stockAwal; // Tidak ada perubahan jika status tidak sesuai
            }
        } else {
            // Jika ada transaksi sebelumnya
            $stockSementara = $lastTransaction->stockSementara; // Ambil stockSementara dari transaksi terakhir
            if ($request->status === 'Pending') {
                // Jika statusnya Pending, tidak ada perubahan pada stockSementara
                $stockSementara = $lastTransaction->stockSementara; // Tetap menggunakan nilai sebelumnya
            } elseif ($type === 'Masuk' && $request->status === 'Diterima') {
                $stockSementara += $request->quantity; // Tambah quantity untuk transaksi masuk
            } elseif ($type === 'Keluar' && $request->status === 'Dikeluarkan') {
                if ($stockSementara < $request->quantity) {
                    return response()->json(['error' => 'Stock tidak cukup untuk transaksi ini.'], 400);
                }
                $stockSementara -= $request->quantity; // Kurangi quantity untuk transaksi keluar
            } else {
                // Jika status tidak sesuai, tidak ada perubahan pada stockSementara
                $stockSementara = $lastTransaction->stockSementara; // Tetap menggunakan nilai sebelumnya
            }
        }

        Transaction::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'status' => 'Pending',
            'notes' => $request->notes,
            'type' => ucfirst($type),
            'stockSementara' => $stockSementara, // Simpan stockSementara
        ]);

        // Memperbarui stockSementara untuk transaksi berikutnya
        $this->updateStockSementara($request->product_id);

        return redirect()->route('stock.transaction.tampil', ['type'=> $type])->with('success', 'Transaction created successfully.');
    }

    public function delete($type, $id)
    {
        $transaction = Transaction::where('id', $id)->where('type', $type)->first();

        if ($transaction) {
            $transaction->delete();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah mengubah status transaksi ' . strtolower($transaction->type) . ' menjadi ' . $request->status, // Menyimpan aktivitas perubahan status
        ]);
        // Memperbarui stockSementara setelah status diubah
        $this->updateStockSementara($transaction->product_id);
        return response()->json(['message' => 'Status updated successfully.']);
    }
    public function updateStockSementara($product_id)
    {
        $transactions = Transaction::where('product_id', $product_id)->orderBy('id')->get();
        $stockSementaraLast = Product::find($product_id)->stock;

        foreach ($transactions as $transaction) {
            if ($transaction->type === 'Masuk' && $transaction->status === 'Diterima') {
                $stockSementaraLast += $transaction->quantity; // Update stockSementara
                $transaction->update(['stockSementara' => $stockSementaraLast]);
            } elseif ($transaction->type === 'Keluar' && $transaction->status === 'Dikeluarkan') {
                $stockSementaraLast -= $transaction->quantity; // Update stockSementara
                $transaction->update(['stockSementara' => $stockSementaraLast]);
            } else {
                // Jika status tidak sesuai, tetap menggunakan nilai sebelumnya
                $transaction->update(['stockSementara' => $stockSementaraLast]);
            }
        }
    }
}
