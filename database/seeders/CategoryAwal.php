<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryAwal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Elektronik bisa nyetrum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perabotan',
                'description' => 'Perabot rumah tangga.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATK',
                'description' => 'Alat tulis kantor.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Obat-obatan',
                'description' => 'Obat buat tubuh.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perhiasan',
                'description' => 'Perhiasan bagus.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Makanan',
                'description' => 'enak.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($categories as $categoryData){
            Category::create($categoryData);
        }
    }
}
