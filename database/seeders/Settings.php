<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Settings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = [
            [
                'app_name' => 'LadyBug',
                'logo' => 'images/settings/ladybug.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($setting as $settingData) {
            // Salin gambar ke storage/public jika belum ada
            $sourcePath = public_path($settingData['logo']);
            $targetPath = 'public/' . $settingData['logo'];
            
            if (!Storage::exists($targetPath)) {
                Storage::put($targetPath, file_get_contents($sourcePath));
            }
        
            // Simpan data setting ke database
            Setting::create($settingData);
        }
    }
}
