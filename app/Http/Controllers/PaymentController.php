<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store() {}


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }
    public function update($id) {}

    public function destroy($id)
    {
        //
    }
}
