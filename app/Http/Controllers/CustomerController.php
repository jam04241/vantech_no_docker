<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function index()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }

    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
        Customer::create($data);
        return redirect()->route('pos.itemlist')
            ->with('success', 'Customer created successfully.')
            ->with('from_customer_add', true); // Add flag to indicate redirect from customer add
    }

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

    public function searchCustomers()
    {
        $query = request()->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where(function ($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('contact_no', 'LIKE', "%{$query}%");
        })
            ->select('id', 'first_name', 'last_name', 'contact_no')
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'full_name' => trim("{$customer->first_name} {$customer->last_name}"),
                    'contact_no' => $customer->contact_no
                ];
            });

        return response()->json($customers);
    }
}
