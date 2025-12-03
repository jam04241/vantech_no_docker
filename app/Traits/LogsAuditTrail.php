<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

trait LogsAuditTrail
{
    /**
     * Log an audit trail entry
     */
    protected function logAudit($action, $module, $description, $changes = null, $request = null)
    {
        try {
            $user = auth()->user();
            $ipAddress = $request ? $request->ip() : request()->ip();

            $this->callStoredProcedure(
                'sp_insert_audit_log',
                [
                    $user->id,
                    $action,
                    $module,
                    $description,
                    $changes ? json_encode($changes) : json_encode([])
                ],
                $ipAddress
            );
        } catch (\Exception $e) {
            Log::error('Failed to log audit trail: ' . $e->getMessage());
        }
    }

    /**
     * Call stored procedure for audit logging (database-agnostic)
     */
    protected function callStoredProcedure($procedureName, $params, $ipAddress = null)
    {
        try {
            $user = auth()->user();
            $driver = DB::getDriverName();

            if ($driver === 'mysql') {
                $placeholders = "?,?,?,?,?";
                DB::statement("CALL $procedureName($placeholders)", $params);
            } elseif ($driver === 'sqlsrv') {
                $placeholders = "@param1,@param2,@param3,@param4,@param5";
                DB::statement("EXEC $procedureName $placeholders", $params);
            }
        } catch (\Exception $e) {
            // Fallback to Eloquent
            try {
                AuditLog::create([
                    'user_id' => auth()->user()->id ?? null,
                    'action' => $params[1] ?? 'Unknown',
                    'module' => $params[2] ?? 'System',
                    'description' => $params[3] ?? '',
                    'changes' => $params[4] ?? null,
                    'ip_address' => $ipAddress,
                ]);
            } catch (\Exception $fallbackError) {
                Log::error('Audit logging failed completely: ' . $fallbackError->getMessage());
            }
        }
    }

    /**
     * Log a create action
     */
    protected function logCreateAudit($action, $module, $description, $data = null, $request = null)
    {
        $changes = $data ? ['created_data' => $data] : null;
        $this->logAudit($action, $module, $description, $changes, $request);
    }

    /**
     * Log an update action
     */
    protected function logUpdateAudit($action, $module, $description, $oldData, $newData, $request = null)
    {
        $changes = [
            'old_data' => $oldData,
            'new_data' => $newData,
        ];
        $this->logAudit($action, $module, $description, $changes, $request);
    }

    /**
     * Log a POS sale action
     * Automatically calculates description from customer and items
     */
    protected function logSaleAudit($module, $customer, $totalQuantity, $totalPrice, $request = null)
    {
        // Get customer full name from relationship
        $customerName = $customer->first_name . ' ' . $customer->last_name;

        // Create description: "Sold {quantity} items to {customer_name} (Total: {total_price})"
        $description = "Sold {$totalQuantity} items to {$customerName} (Total: {$totalPrice})";

        // Create changes data with sale details
        $changes = [
            'customer_id' => $customer->id,
            'customer_name' => $customerName,
            'quantity' => $totalQuantity,
            'total_price' => $totalPrice
        ];

        // Log with 'Sold' action for POS module
        $this->logAudit('CREATE', $module, $description, $changes, $request);
    }
}
