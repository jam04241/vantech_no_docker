<?php

namespace App\Http\Controllers;

use App\Models\ServiceReplacement;
use App\Http\Requests\ServiceReplacementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceReplacementController extends Controller
{
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
     * Store a newly created replacement in storage.
     */
    public function store(ServiceReplacementRequest $request)
    {
        try {
            $validated = $request->validated();

            // Convert is_disabled to boolean (in case it comes as string '0' or '1')
            if (isset($validated['is_disabled'])) {
                $validated['is_disabled'] = (bool) $validated['is_disabled'];
            } else {
                $validated['is_disabled'] = false;
            }

            $replacement = ServiceReplacement::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Replacement added successfully.',
                'replacement' => $replacement,
                'id' => $replacement->id
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create replacement: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update the specified replacement in storage (including soft delete via is_disabled).
     */
    public function update(ServiceReplacementRequest $request, ServiceReplacement $serviceReplacement)
    {
        try {
            $validated = $request->validated();

            // Convert is_disabled to boolean (in case it comes as string '0', '1', or boolean)
            if (isset($validated['is_disabled'])) {
                $validated['is_disabled'] = (bool) $validated['is_disabled'];
            }

            $serviceReplacement->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Replacement updated successfully.',
                'id' => $serviceReplacement->id,
                'replacement' => $serviceReplacement
            ], 200);
        } catch (\Exception $e) {
            Log::error('ServiceReplacement update error: ' . $e->getMessage(), ['replacement_id' => $serviceReplacement->id]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update replacement: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified replacement from storage.
     */
    public function destroy(ServiceReplacement $serviceReplacement)
    {
        $serviceReplacement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Replacement deleted successfully.'
        ]);
    }
}
