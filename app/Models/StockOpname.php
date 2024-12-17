<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $table = 'stockOpname';
    protected $fillable = [
        'product_id',
        'category_id',
        'masuk',
        'keluar',
        'stock_akhir',
        'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
