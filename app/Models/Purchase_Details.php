<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Details extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'product_id',
        'supplier_id',
        'bundle_id',
        'quantity_ordered',
        'unit_price',
        'total_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundles::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }
}
