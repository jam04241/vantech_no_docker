<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'email',
        'contactNo',
        'street',
        'brgy',
        'cityProvince',
        // Define fillable attributes here
    ];
}
