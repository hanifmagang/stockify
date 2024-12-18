<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Exports\ActivityExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ActivityController extends Controller
{
    public function tampil(){
        $activities = Activity::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('laporan.activity.tampil', compact('activities'));
    }

    public function submit(Request $request){
        Activity::create([
            'user_id' => auth()->id(),
            'activity' => $request->activity,
        ]);
    }
    public function exportToExcel()
    {
        $date = now()->format('Y-m-d');
        $query = Activity::with('user')->orderBy('created_at', 'desc');

        // Tambahkan filter berdasarkan tanggal


        $activity = $query->get();

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export excel laporan user activity ', 
        ]);

        return Excel::download(new ActivityExport($activity), 'laporan aktivitas_'  . $date . '.xlsx');
    }
    public function exportToPDF()
    {
        $date = now()->format('Y-m-d');
        $query = Activity::with('user')->orderBy('created_at', 'desc'); // Gunakan Activity bukan Transaction

        $activities = $query->get();

        $pdf = PDF::loadView('laporan.activity', compact('activities')); // Ubah view dan compact data

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => 'User telah melakukan export pdf laporan user activity', // Ubah deskripsi aktivitas
        ]);

        return $pdf->stream('laporan aktivitas_' . $date . '.pdf'); // Ubah nama file output
    }
}
