<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class Dataawal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $user = [
            [
                'name' => 'Admin',
                'email' => 'admin@contoh.com',
                'password' => bcrypt('12345678'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Hanif',
                'email' => 'hanif@contoh.com',
                'password' => bcrypt('12345678'),
                'role' => 'Manajer Gudang',
            ],
            [
                'name' => 'Ichsan',
                'email' => 'ichsan@contoh.com',
                'password' => bcrypt('12345678'),
                'role' => 'Staff Gudang',
            ]
        ];
        
        foreach ($user as $userData){
            User::create($userData);
        }
    }
}
