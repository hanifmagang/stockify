<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions->map(function ($transaction) {
            return [
                'Product' => $transaction->product->name,
                'User' => $transaction->user->name,
                'Type' => $transaction->type,
                'Date' => $transaction->date,
                'Status' => $transaction->status,
                'Created_at' => $transaction->created_at,
                'Updated_at' => $transaction->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product',
            'User',
            'Type',
            'Date',
            'Status',
            'Created_at',
            'Updated_at',
        ];
    }
}