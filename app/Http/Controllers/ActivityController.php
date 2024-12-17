<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

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
}
