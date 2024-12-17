<?php

namespace App\Exports;


use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromCollection, WithHeadings
{
    
    protected $transactions;
    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }
    public function collection()
    {
        // Mengambil data StockOpname untuk ekspor
        return $this->transactions->map(function ($transaction) {
            return [
                'SKU' => $transaction->product->sku,
                'Category' => $transaction->product->category->name,
                'Product Name' => $transaction->product->name,
                'Quantity Change' => $transaction->quantity,
                'Qurrent Stock' => $transaction->stockSementara,
                'Date' => $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Category',
            'Product Name',
            'Quantity Change',
            'Qurrent Stock',
            'Date',
        ];
    }
}
