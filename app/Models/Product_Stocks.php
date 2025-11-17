<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Stocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_quantity',
        'price',
        'product_id',
    ];

    public function product(){
        return $this->hasMany(Product::class);
    }
}
