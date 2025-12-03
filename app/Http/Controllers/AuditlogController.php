<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get filter parameters from request
        $search = $request->get('search', '');
        $module = $request->get('module', '');
        $action = $request->get('action', '');
        $sortBy = $request->get('sortBy', 'created_at');
        $sortOrder = $request->get('sortOrder', 'desc');
        $perPage = $request->get('perPage', 15);

        // Build query
        $query = AuditLog::with('user');

        // Apply search filter - search across multiple fields
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search in user's first name or last name (checks individual fields)
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })
                    // Also search in other audit log fields
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });

            // When doing a search, ignore the module and action filters
            // This allows searching across all modules and actions for a specific user
        } else {
            // Only apply module and action filters when NOT searching
            if (!empty($module)) {
                $query->where('module', $module);
            }

            if (!empty($action)) {
                $query->where('action', $action);
            }
        }

        // Apply sorting
        if (in_array($sortBy, ['created_at', 'action', 'module'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Paginate results
        $auditLogs = $query->paginate($perPage);

        // If HTMX request, return only table partial
        if ($request->header('HX-Request')) {
            return view('DASHBOARD.audit-table', compact('auditLogs', 'search', 'module', 'action', 'sortBy', 'sortOrder'));
        }

        // Regular request, return full page
        return view('DASHBOARD.audit', compact('auditLogs', 'search', 'module', 'action', 'sortBy', 'sortOrder'));
    }
}
