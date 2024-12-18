<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
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
                'logo' => 'storage/app/public/images/settings/ladybug.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($setting as $settingData) {
            Setting::create($settingData);
        }
    }
}
