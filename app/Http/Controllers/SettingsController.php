<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function tampil(){

        $settings = Setting::first();
        return view('settings', compact('settings'));
    }
    public function submit(Request $request){
        $validatedData = Validator::make($request->all(),[
            'app_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        if ($request->hasFile('logo')) {
            $imagePath = $request->file('logo')->store('images/settings', 'public');
        } else {
            $imagePath = null;
        }

        Setting::create([
            'app_name' => $request->app_name,
            'logo' => $imagePath,
        ]);
        return redirect()->route('settings')->with('success', 'Data created successfully.');
    }
    function update(Request $request, $id){
        $settings = Setting::find($id);
        $oldData = $settings->replicate();
        $settings->app_name = $request->app_name;
        

        // Upload image jika ada
        if ($request->hasFile('logo')) {
            $imagePath = $request->file('logo')->store('images/settings', 'public');
            $settings->logo = $imagePath; // Update image path
        }

        $settings->save(); // Simpan perubahan

        $changes = [];
        if ($oldData->app_name !== $settings->app_name) {
            $changes[] = 'nama';
        }
        if ($oldData->logo !== $settings->logo) {
            $changes[] = 'logo';
        }

        if (!empty($changes)) {
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => 'User telah mengubah ' . implode(', ', $changes) . ' aplikasi', 
            ]);
        }
        return redirect()->route('settings');
    }
}
