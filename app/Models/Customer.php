<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',      // Required
        'last_name',       // Required
        'gender',          // Required
        'contact_no',      // Required
        'street',          // Optional
        'brgy',            // Optional
        'city_province',   // Optional
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(CustomerPurchaseOrder::class);
    }
}
