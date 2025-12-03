# Audit Logging for Inventory Operations

## Overview

Comprehensive audit logging has been implemented for all inventory operations (Brand, Category, and Product management). Every create and update action is automatically logged to the `auditlogs` table with detailed descriptions and change tracking.

---

## Implementation Details

### Files Modified

1. **BrandController.php** - Added audit logging for brand create/update
2. **CategoryController.php** - Added audit logging for category create/update
3. **ProductController.php** - Added audit logging for product create/update/price update
4. **LogsAuditTrail.php** (NEW) - Shared trait for audit logging functionality

---

## Audit Logging Operations

### 1. Add Brand
- **File:** `BrandController.php` → `store()` method
- **Module:** `Inventory`
- **Action:** `Create`
- **Description Format:** `Added a new Brand {brand_name}`
- **Example:** `Added a new Brand Dell`

**Code:**
```php
$description = "Added a new Brand {$validated['brand_name']}";
$this->logCreateAudit('Create', 'Inventory', $description, $validated, $request);
```

---

### 2. Add Category
- **File:** `CategoryController.php` → `store()` method
- **Module:** `Inventory`
- **Action:** `Create`
- **Description Format:** `Added a new Category {category_name}`
- **Example:** `Added a new Category Laptop`

**Code:**
```php
$description = "Added a new Category {$validated['category_name']}";
$this->logCreateAudit('Create', 'Inventory', $description, $validated, $request);
```

---

### 3. Add Product
- **File:** `ProductController.php` → `store()` method
- **Module:** `Inventory`
- **Action:** `Create`
- **Description Format:** `Added new product: {brand_name} {product_name} (SKU: {serial_number})`
- **Example:** `Added new product: Dell XPS 15 (SKU: XPS-2024-001)`

**Code:**
```php
$brand = $product->brand;
$brandName = $brand ? $brand->brand_name : 'Unknown';
$description = "Added new product: {$brandName} {$data['product_name']} (SKU: {$data['serial_number']})";
$logData = array_merge($data, ['price' => $price]);
$this->logCreateAudit('Create', 'Inventory', $description, $logData, $request);
```

---

### 4. Update Product
- **File:** `ProductController.php` → `update()` method
- **Module:** `Inventory`
- **Action:** `Update`
- **Description Format:** `Update {condition} for {product_name}: {last} -> {updated}`

**Condition Types:**
- `Price` - When price is updated
- `Serial No.` - When serial number is updated
- `Detail` - When other details are updated

**Examples:**
- `Update Price for XPS 15: 1500 -> 1750`
- `Update Serial No. for XPS 15: XPS-2024-001 -> XPS-2024-002`
- `Update Detail for XPS 15: {...old_data...} -> {...new_data...}`

**Code:**
```php
if (isset($data['price']) && $oldStock && $oldStock['price'] != $data['price']) {
    $condition = 'Price';
    $lastValue = $oldStock['price'];
    $updatedValue = $data['price'];
} elseif (isset($data['serial_number']) && $oldData['serial_number'] != $data['serial_number']) {
    $condition = 'Serial No.';
    $lastValue = $oldData['serial_number'];
    $updatedValue = $data['serial_number'];
} else {
    $condition = 'Detail';
    // ... handle other details
}

$description = "Update {$condition} for {$product->product_name}: {$lastValue} -> {$updatedValue}";
$this->logUpdateAudit('Update', 'Inventory', $description, $oldData, $newData, $request);
```

---

### 5. Update Brand
- **File:** `BrandController.php` → `update()` method
- **Module:** `Inventory`
- **Action:** `Update`
- **Description Format:** `Update {last brand_name} ->{new brand_name}`
- **Example:** `Update Dell ->Dell Inc.`

**Code:**
```php
$description = "Update {$oldData['brand_name']} ->{$validated['brand_name']}";
$this->logUpdateAudit('Update', 'Inventory', $description, $oldData, $validated, $request);
```

---

### 6. Update Category
- **File:** `CategoryController.php` → `update()` method
- **Module:** `Inventory`
- **Action:** `Update`
- **Description Format:** `Update {last category_name} ->{new category_name}`
- **Example:** `Update Laptop ->Notebooks`

**Code:**
```php
$description = "Update {$oldData['category_name']} ->{$validated['category_name']}";
$this->logUpdateAudit('Update', 'Inventory', $description, $oldData, $validated, $request);
```

---

## Technical Architecture

### LogsAuditTrail Trait

Located in: `app/Traits/LogsAuditTrail.php`

**Methods:**

#### 1. `logAudit($action, $module, $description, $changes, $request)`
Main method for logging audit entries. Attempts to use stored procedure, falls back to Eloquent ORM.

```php
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
        \Log::error('Failed to log audit trail: ' . $e->getMessage());
    }
}
```

#### 2. `callStoredProcedure($procedureName, $params, $ipAddress)`
Database-agnostic method that detects DB driver and uses appropriate syntax.

```php
protected function callStoredProcedure($procedureName, $params, $ipAddress = null)
{
    try {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $placeholders = "?,?,?,?,?";
            DB::statement("CALL $procedureName($placeholders)", $params);
        } elseif ($driver === 'sqlsrv') {
            $placeholders = "@param1,@param2,@param3,@param4,@param5";
            DB::statement("EXEC $procedureName $placeholders", $params);
        }
    } catch (\Exception $e) {
        // Fallback to Eloquent...
    }
}
```

#### 3. `logCreateAudit($action, $module, $description, $data, $request)`
Convenience method for logging create operations.

```php
protected function logCreateAudit($action, $module, $description, $data = null, $request = null)
{
    $changes = $data ? ['created_data' => $data] : null;
    $this->logAudit($action, $module, $description, $changes, $request);
}
```

#### 4. `logUpdateAudit($action, $module, $description, $oldData, $newData, $request)`
Convenience method for logging update operations.

```php
protected function logUpdateAudit($action, $module, $description, $oldData, $newData, $request = null)
{
    $changes = [
        'old_data' => $oldData,
        'new_data' => $newData,
    ];
    $this->logAudit($action, $module, $description, $changes, $request);
}
```

---

## Error Handling

### 3-Layer Fallback System

1. **Layer 1:** Stored Procedure Execution
   - Attempts to call the stored procedure via `DB::statement()`
   - Database-agnostic (MySQL or SQL Server)

2. **Layer 2:** Eloquent ORM Fallback
   - If stored procedure fails, directly creates audit log via `AuditLog::create()`
   - Ensures record is created even if procedure is unavailable

3. **Layer 3:** Error Logging
   - If both previous layers fail, error is logged to `storage/logs/laravel.log`
   - Prevents application errors from blocking operations

**Result:** Operations never fail, logging is always attempted

---

## Database Schema

**Table:** `auditlogs`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users table |
| action | varchar(50) | Action type (Create, Update, Delete) |
| module | varchar(50) | Module name (Inventory, Authentication) |
| description | text | Human-readable description |
| changes | json | Detailed change data |
| ip_address | varchar(45) | IP address of user |
| created_at | timestamp | Timestamp of action |
| updated_at | timestamp | Last updated timestamp |

---

## Usage Examples

### Example 1: Add Brand (in Inventory)
```
User Action: Click "Add Brand" → Enter "Dell" → Click "Create"

Audit Log Entry:
{
  user_id: 1,
  action: "Create",
  module: "Inventory",
  description: "Added a new Brand Dell",
  changes: {
    created_data: {
      brand_name: "Dell"
    }
  },
  ip_address: "192.168.1.100"
}
```

### Example 2: Update Product Price
```
User Action: Click "Edit Product" → Change Price: 1500 → 1750 → Save

Audit Log Entry:
{
  user_id: 1,
  action: "Update",
  module: "Inventory",
  description: "Update Price for XPS 15: 1500 -> 1750",
  changes: {
    old_data: { price: 1500, ... },
    new_data: { price: 1750, ... }
  },
  ip_address: "192.168.1.100"
}
```

### Example 3: Update Category Name
```
User Action: Click "Edit Category" → Change "Laptop" to "Notebooks" → Save

Audit Log Entry:
{
  user_id: 1,
  action: "Update",
  module: "Inventory",
  description: "Update Laptop ->Notebooks",
  changes: {
    old_data: { category_name: "Laptop" },
    new_data: { category_name: "Notebooks" }
  },
  ip_address: "192.168.1.100"
}
```

---

## Viewing Audit Logs

### Query Examples

**View all inventory operations:**
```sql
SELECT * FROM auditlogs 
WHERE module = 'Inventory' 
ORDER BY created_at DESC;
```

**View specific user's changes:**
```sql
SELECT * FROM auditlogs 
WHERE user_id = 1 AND module = 'Inventory' 
ORDER BY created_at DESC;
```

**View all brand creations:**
```sql
SELECT * FROM auditlogs 
WHERE action = 'Create' AND module = 'Inventory' AND description LIKE 'Added a new Brand%'
ORDER BY created_at DESC;
```

**View all price updates:**
```sql
SELECT * FROM auditlogs 
WHERE action = 'Update' AND module = 'Inventory' AND description LIKE 'Update Price%'
ORDER BY created_at DESC;
```

---

## Security Considerations

✅ **Parameterized Queries** - All queries use parameter binding (prevents SQL injection)
✅ **User Authentication** - Only authenticated users are logged
✅ **IP Address Tracking** - User's IP is captured for security auditing
✅ **Change Tracking** - Old and new values are preserved for audit trails
✅ **Non-Blocking** - Audit logging failures never block operations

---

## Integration Checklist

- [x] LogsAuditTrail trait created
- [x] BrandController integrated with audit logging
- [x] CategoryController integrated with audit logging
- [x] ProductController integrated with audit logging
- [x] Store procedures verified (existing from AuthController setup)
- [x] Error handling implemented (3-layer fallback)
- [x] Description formats match specifications

---

## Testing

### Manual Testing Steps

1. **Add Brand**
   - Navigate to Add Product page
   - Click "Add Brand" button
   - Enter brand name (e.g., "HP")
   - Click "Create"
   - Check auditlogs table for entry with description "Added a new Brand HP"

2. **Update Product Price**
   - Navigate to Inventory
   - Click edit icon on a product
   - Change price value
   - Click save
   - Check auditlogs table for entry with description "Update Price for {product_name}: {old_price} -> {new_price}"

3. **Verify Data**
   ```sql
   SELECT * FROM auditlogs 
   WHERE module = 'Inventory' 
   ORDER BY created_at DESC 
   LIMIT 10;
   ```

---

## Troubleshooting

**Issue:** Audit logs not appearing

**Solution:**
1. Verify stored procedure exists: `SHOW PROCEDURE STATUS WHERE Name='sp_insert_audit_log';`
2. Check database connection in `.env`
3. Verify user is authenticated before operation
4. Check `storage/logs/laravel.log` for errors

**Issue:** Incorrect description format

**Solution:**
1. Verify data is being passed correctly to logging methods
2. Check template string syntax in controller
3. Ensure all variables are properly populated before logging

---

## Future Enhancements

- Add audit log filtering in inventory dashboard
- Create audit trail reports
- Add export functionality for audit logs
- Implement audit log retention policies
- Add real-time audit log viewer
- Create notifications for critical operations

---

## Files Changed

1. `app/Http/Controllers/BrandController.php` - ✅ Modified
2. `app/Http/Controllers/CategoryController.php` - ✅ Modified
3. `app/Http/Controllers/ProductController.php` - ✅ Modified
4. `app/Traits/LogsAuditTrail.php` - ✅ Created
5. Database stored procedures (existing) - ✅ Used

---

**Implementation Date:** December 4, 2025
**Status:** ✅ Complete and Ready for Testing
