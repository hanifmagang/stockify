<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            ],
            [
                'category_id' => '3',
                'supplier_id' => '1',
                'name' => 'Buku',
                'sku' => 'BK123',
                'stock' => '0',
                'stockMinimum' => '20',
                'description' => 'buat nulis.',
                'purchase_price' => '12000',
                'selling_price' => '15000',
                'image' => 'images/product/buku.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => '1',
                'supplier_id' => '3',
                'name' => 'Mouse',
                'sku' => 'MS123',
                'stock' => '0',
                'stockMinimum' => '10',
                'description' => 'klik klik.',
                'purchase_price' => '75000',
                'selling_price' => '80000',
                'image' => 'images/product/mouse.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => '4',
                'supplier_id' => '2',
                'name' => 'Tolak Angin',
                'sku' => 'TA123',
                'stock' => '0',
                'stockMinimum' => '30',
                'description' => 'buang angin.',
                'purchase_price' => '3000',
                'selling_price' => '4000',
                'image' => 'images/product/tolak.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => '5',
                'supplier_id' => '3',
                'name' => 'Cincin',
                'sku' => 'CC123',
                'stock' => '0',
                'stockMinimum' => '5',
                'description' => 'jari.',
                'purchase_price' => '750000',
                'selling_price' => '850000',
                'image' => 'images/product/cincin.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => '1',
                'supplier_id' => '3',
                'name' => 'Keyboard',
                'sku' => 'KY123',
                'stock' => '0',
                'stockMinimum' => '10',
                'description' => 'buat ngetik.',
                'purchase_price' => '375000',
                'selling_price' => '400000',
                'image' => 'images/product/keyboard.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => '3',
                'supplier_id' => '1',
                'name' => 'Pensil',
                'sku' => 'PS123',
                'stock' => '0',
                'stockMinimum' => '30',
                'description' => 'nuliss.',
                'purchase_price' => '4000',
                'selling_price' => '6000',
                'image' => 'images/product/pensil.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($product as $productData) {
            // Copy gambar ke storage/public jika belum ada
            $sourcePath = public_path($productData['image']);
            $targetPath = 'public/' . $productData['image'];
            
            if (!Storage::exists($targetPath)) {
                Storage::put($targetPath, file_get_contents($sourcePath));
            }

            // Simpan data produk ke database
            $createdProduct = Product::create([
                'category_id' => $productData['category_id'],
                'supplier_id' => $productData['supplier_id'],
                'name' => $productData['name'],
                'sku' => $productData['sku'],
                'stock' => $productData['stock'],
                'stockMinimum' => $productData['stockMinimum'],
                'description' => $productData['description'],
                'purchase_price' => $productData['purchase_price'],
                'selling_price' => $productData['selling_price'],
                'image' => $productData['image'], // Simpan path relatif
            ]);

            StockOpname::create([
                'product_id' => $createdProduct->id,
                'category_id' => $createdProduct->category_id,
                'masuk' => 0, // Atur sesuai kebutuhan
                'keluar' => 0, // Atur sesuai kebutuhan
                'stock_akhir' => $createdProduct->stock, // Atur sesuai kebutuhan
                'date' => now(), // Atur tanggal sesuai kebutuhan
            ]);
        }
    }
    
}
