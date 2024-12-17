<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Activity;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class LaporanController extends Controller
{
    

    public function tampil(Request $request, $type)
    {
        $query = Transaction::with('product', 'user')->where('type', ucfirst($type));

        // Tambahkan filter berdasarkan tanggal
        if ($request->has('start-date') && $request->has('end-date')) {
            $query->whereBetween('updated_at', [$request->input('start-date'), $request->input('end-date')]);
        }

        $transactions = $query->paginate(20);
        
        return view('laporan.transaction.tampil', compact('transactions', 'type'));
    }

    public function exportToExcel(Request $request, $type)
    {
        $date = now()->format('Y-m-d');
        $query = Transaction::with('product', 'user')->where('type', ucfirst($type));

        // Tambahkan filter berdasarkan tanggal
        if ($request->has('start-date') && $request->has('end-date')) {
            $query->whereBetween('updated_at', [$request->input('start-date'), $request->input('end-date')]);
        }

        $transactions = $query->get();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export excel laporan transaksi ' . strtolower($type). '' , 
        ]);

        return Excel::download(new TransactionsExport($transactions), 'transaksi_' . $type . '_' . $date . '.xlsx');
    }

    public function exportToPDF(Request $request, $type)
    {
        $date = now()->format('Y-m-d');
        $query = Transaction::with('product', 'user')->where('type', ucfirst($type));

        // Tambahkan filter berdasarkan tanggal
        if ($request->has('start-date') && $request->has('end-date')) {
            $query->whereBetween('updated_at', [$request->input('start-date'), $request->input('end-date')]);
        }

        $transactions = $query->get();

        $pdf = PDF::loadView('laporan.transaction_pdf', compact('transactions', 'type'));

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export pdf laporan transaksi ' . strtolower($type). '' , 
        ]);

        return $pdf->stream('transaksi_' . $type . '_' . $date . '.pdf');
    }

    
}
