<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundles extends Model
{
    use HasFactory;

    protected $fillable = [
        'bundle_name',
        'bundle_type',
    ];
}
