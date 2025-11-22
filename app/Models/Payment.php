<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_date',
        'payment_method',
        'transaction_id',
        'customer_id',
        'order_id',
        'status',
    ];
}
