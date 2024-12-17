<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAwal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $product = [
            [
                'category_id' => '2',
                'supplier_id' => '2',
                'name' => 'Meja',
                'sku' => 'MJ123',
                'stock' => '0',
                'stockMinimum' => '5',
                'description' => 'Dari kayu.',
                'purchase_price' => '50000',
                'selling_price' => '60000',
                'image' => 'images/product/meja.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
            ];
        foreach ($product as $productData) {
            Product::create($productData);
        }
    }
    
}
