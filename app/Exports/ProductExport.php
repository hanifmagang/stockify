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
            return $product->only([
                'category_id',
                'supplier_id',
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
            ]);
        });
    }

    public function headings(): array
    {
        return [
            'category_id',
            'supplier_id',
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