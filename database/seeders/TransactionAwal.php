<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionAwal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'product_id' => '1', 
                'user_id' => 2,
                'type' => 'Masuk',
                'quantity' => 20,
                'date' => now(),
                'status' => 'Pending',
                'notes' => null,
                'stockSementara' => '0',
            ],
            [
                'product_id' => '1', 
                'user_id' => 2,
                'type' => 'Keluar',
                'quantity' => 5,
                'date' => now(),
                'status' => 'Pending',
                'notes' => null,
                'stockSementara' => '0',
            ],
        ];
        
        foreach ($transactions as $transData){
            Transaction::create($transData);
        }
    }
}
