<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::insert(self::$paymentMethods);
    }
    /**
     * The car model data.
     *
     * @var array
     */
    protected static $paymentMethods   = [
        ['method_name' => 'Cash'],
        ['method_name' => 'Credit Card'],
        ['method_name' => 'Gcash'],
        ['method_name' => 'BPI'],
        ['method_name' => 'Others'],
    ];
}
