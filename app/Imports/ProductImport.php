<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\StockOpname;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $product = Product::create([
            'category_id' => $row['category_id'],
            'supplier_id' => $row['supplier_id'],
            'name' => $row['name'],
            'sku' => $row['sku'],
            'stock' => $row['stock'],
            'stockMinimum' => $row['stockMinimum'],
            'description' => $row['description'],
            'purchase_price' => $row['purchase_price'],
            'selling_price' => $row['selling_price'],
            'image' => $row['image'], 
        ]);

        StockOpname::create([
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'masuk' => 0, // Atur sesuai kebutuhan
            'keluar' => 0, // Atur sesuai kebutuhan
            'stock_akhir' => 0, // Atur sesuai kebutuhan
            'date' => now(), // Atur tanggal sesuai kebutuhan
        ]);

        return $product;
    }
}
