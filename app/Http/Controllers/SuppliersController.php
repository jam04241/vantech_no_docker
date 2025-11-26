<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Http\Requests\SupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuppliersController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Build query with search functionality
        $suppliersQuery = Suppliers::query();
        
        if ($search) {
            $suppliersQuery->where(function($query) use ($search) {
                $query->where('supplier_name', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%")
                      ->orWhere('contact_phone', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        // Order by creation date or name
        $suppliersQuery->orderBy('created_at', 'desc');
        
        // Paginate results
        $suppliers = $suppliersQuery->paginate(10); // 10 items per page
        
        // Get counts for statistics (from all records, not just paginated)
        $totalSuppliers = Suppliers::count();
        $activeCount = Suppliers::where('status', 'active')->count();
        $inactiveCount = Suppliers::where('status', 'inactive')->count();
        
        return view('DASHBOARD.suppliers', compact('suppliers', 'totalSuppliers', 'activeCount', 'inactiveCount', 'search'));
    }

    public function store(SupplierRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'active'; // Set default status
            Suppliers::create($data);
            
            return redirect()->route('suppliers')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers')->with('error', 'Error creating supplier: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $supplier = Suppliers::findOrFail($id);
            return response()->json($supplier);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $supplier = Suppliers::findOrFail($id);

            $validated = $request->validate([
                'supplier_name' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $supplier->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Supplier updated successfully',
                'supplier' => $supplier
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update supplier'
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $supplier = Suppliers::findOrFail($id);
            
            $supplier->status = $supplier->status === 'active' ? 'inactive' : 'active';
            $supplier->save();
            
            return response()->json([
                'success' => true, 
                'status' => $supplier->status,
                'message' => 'Supplier status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating supplier status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    
}
}