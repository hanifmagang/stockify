<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('category', 'supplier')->get()->map(function ($product) {
            return [
                'category' => $product->category->name, 
                'supplier' => $product->supplier->name, 
                'name' => $product->name,
                'sku' => $product->sku,
                'stock' => $product->stock,
                'stockMinimum' => $product->stockMinimum,
                'description' => $product->description,
                'purchase_price' => $product->purchase_price,
                'selling_price' => $product->selling_price,
                'image' => $product->image,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'category',
            'supplier',
            'name',
            'sku',
            'stock',
            'stockMinimum',
            'description',
            'purchase_price',
            'selling_price',
            'image',
            'created_at',
            'updated_at',
        ];
    }
}