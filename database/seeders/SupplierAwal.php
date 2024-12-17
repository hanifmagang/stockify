<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierAwal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = [
            [
                'name' => 'Hanif',
                'address' => 'Janti',
                'phone' => '08817677305',
                'email' => 'hanif@contoh.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ichsan',
                'address' => 'Banguntapan',
                'phone' => '087714449527',
                'email' => 'ichsan@contoh.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maulana',
                'address' => 'Karangjambe',
                'phone' => '089637087766',
                'email' => 'maulana@contoh.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($supplier as $supplierData){
            Supplier::create($supplierData);
        }
        
    }
}
