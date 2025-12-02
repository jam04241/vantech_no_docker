<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;
use App\Http\Requests\ServiceRequest;
use App\Traits\LoadsBrandData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    use LoadsBrandData;
    /**
     * Display a listing of all services.
     */
    public function index()
    {
        $services = Service::with(['customer', 'serviceType'])->get();
        $serviceTypes = ServiceType::all();
        $customers = \App\Models\Customer::all();

        return view('ServicesOrder.Services', [
            'services' => $services,
            'serviceTypes' => $serviceTypes,
            'customers' => $customers,
        ]);
    }

    /**
     * Store a newly created service in database.
     */
    public function store(ServiceRequest $request)
    {
        $service = Service::create($request->validated());

        // Load relationships but exclude replacements to avoid table-not-found errors
        $service->load(['customer', 'serviceType']);

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'service' => $service
        ]);
    }

    /**
     * Show the specified service with its replacements.
     */
    public function show(Service $service)
    {
        // Load customer, serviceType, and replacements relationships
        $service->load(['customer', 'serviceType', 'replacements']);

        return response()->json([
            'id' => $service->id,
            'customer_id' => $service->customer_id,
            'service_type_id' => $service->service_type_id,
            'customer' => $service->customer ? [
                'id' => $service->customer->id,
                'first_name' => $service->customer->first_name,
                'last_name' => $service->customer->last_name,
            ] : null,
            'serviceType' => $service->serviceType ? [
                'id' => $service->serviceType->id,
                'name' => $service->serviceType->name,
                'price' => $service->serviceType->price,
            ] : null,
            'type' => $service->type,
            'brand' => $service->brand,
            'model' => $service->model,
            'date_in' => $service->date_in,
            'date_out' => $service->date_out,
            'description' => $service->description,
            'action' => $service->action,
            'status' => $service->status,
            'total_price' => $service->total_price,
            'replacements' => $service->replacements ?? [],
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
        ]);
    }

    /**
     * Update the specified service in database.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        // Load relationships but exclude replacements to avoid table-not-found errors
        $service->load(['customer', 'serviceType']);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'service' => $service
        ]);
    }

    /**
     * Archive (cancel) the specified service.
     */
    public function archive(Service $service)
    {
        $service->update(['status' => 'Canceled']);

        return response()->json([
            'success' => true,
            'message' => 'Service archived successfully.',
            'service' => $service
        ]);
    }

    /**
     * Remove the specified service from database.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.'
        ]);
    }

    // API endpoint for Services module - get all service types
    public function getServiceTypes()
    {
        $types = Service::distinct()->whereNotNull('type')->pluck('type')->sort()->values();

        return response()->json($types);
    }

    /**
     * API: Get all services with optional filtering and search
     */
    public function apiList(Request $request)
    {
        // Load customer, serviceType, and replacements relationships
        $query = Service::with(['customer', 'serviceType', 'replacements']);

        // Filter by status (exclude Canceled from "All" filter)
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else if ($request->has('status') && $request->status === 'all') {
            // For "All" filter, exclude Canceled status
            $query->whereNotIn('status', ['Canceled']);
        }

        // Search by multiple fields
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })
                    ->orWhereHas('serviceType', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('total_price', 'like', "%{$search}%");
            });
        }

        $services = $query->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'customer_id' => $service->customer_id,
                'service_type_id' => $service->service_type_id,
                'customer' => $service->customer ? [
                    'id' => $service->customer->id,
                    'first_name' => $service->customer->first_name,
                    'last_name' => $service->customer->last_name,
                ] : null,
                'serviceType' => $service->serviceType ? [
                    'id' => $service->serviceType->id,
                    'name' => $service->serviceType->name,
                    'price' => $service->serviceType->price,
                ] : null,
                'type' => $service->type,
                'brand' => $service->brand,
                'model' => $service->model,
                'date_in' => $service->date_in,
                'date_out' => $service->date_out,
                'description' => $service->description,
                'action' => $service->action,
                'status' => $service->status,
                'total_price' => $service->total_price,
                'replacements' => $service->replacements ?? [],
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });

        Log::info('📋 API Services Response - Mapped:', [
            'count' => $services->count(),
            'first_service' => $services->first(),
        ]);

        return response()->json($services);
    }
}
