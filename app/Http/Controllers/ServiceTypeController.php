<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use App\Http\Requests\ServiceTypeRequest;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceTypeController extends Controller
{
    use LogsAuditTrail;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ServiceTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceTypeRequest $request)
    {
        try {
            Log::info('ServiceType store request received', ['data' => $request->all()]);

            $serviceType = ServiceType::create([
                'name' => $request->validated()['name'],
                'price' => $request->validated()['price'],
            ]);

            // Log the audit trail
            $this->logAddServiceTypeAudit($serviceType->name, $serviceType->price, $request);

            Log::info('ServiceType created successfully', ['id' => $serviceType->id, 'name' => $serviceType->name]);

            return response()->json([
                'success' => true,
                'message' => 'Service Type created successfully',
                'data' => $serviceType
            ], 201);
        } catch (\Exception $e) {
            Log::error('ServiceType store error: ' . $e->getMessage(), ['request' => $request->all(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating service type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceType $serviceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceType $serviceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ServiceTypeRequest  $request
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceTypeRequest $request, ServiceType $serviceType)
    {
        try {
            Log::info('ServiceType update request received', ['id' => $serviceType->id, 'data' => $request->all()]);

            $oldName = $serviceType->name;
            $oldFee = $serviceType->price;
            $newName = $request->validated()['name'];
            $newFee = $request->validated()['price'];

            $serviceType->update([
                'name' => $newName,
                'price' => $newFee,
            ]);

            // Log audit trail for each change
            if ($oldName !== $newName) {
                $this->logUpdateServiceNameAudit($oldName, $newName, $request);
            }
            if ($oldFee != $newFee) {
                $this->logUpdateServiceFeeAudit($newName, $oldFee, $newFee, $request);
            }

            Log::info('ServiceType updated successfully', ['id' => $serviceType->id, 'name' => $serviceType->name]);

            return response()->json([
                'success' => true,
                'message' => 'Service Type updated successfully',
                'data' => $serviceType
            ], 200);
        } catch (\Exception $e) {
            Log::error('ServiceType update error: ' . $e->getMessage(), ['service_type_id' => $serviceType->id, 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating service type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceType $serviceType)
    {
        //
    }

    // API endpoint for Services module - get all service types
    public function getApiList()
    {
        $serviceTypes = ServiceType::all()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'price' => $type->price
            ];
        });

        return response()->json($serviceTypes);
    }
}
