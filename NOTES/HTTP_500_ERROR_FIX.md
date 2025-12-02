# HTTP 500 Error Fix - Service Replacements Table

## Problem
User was getting: **Error loading services: Error: HTTP 500: Internal Server Error**

## Root Cause
The Laravel error logs showed:
```
SQLSTATE[42S02]: Invalid object name 'service_replacements'
```

The `service_replacements` table does not exist in the database, but the controller was trying to load relationships from it:
```php
$query = Service::with(['customer', 'serviceType', 'replacements' => function ...])
```

## Solution
Removed the `replacements` relationship loading from all methods in `ServicesController.php`:

### Methods Fixed:
1. **index()** - Line 17
   - ❌ Was: `Service::with(['customer', 'serviceType', 'replacements'])`
   - ✅ Now: `Service::with(['customer', 'serviceType'])`

2. **store()** - Line 34
   - ❌ Was: `load(['customer', 'serviceType', 'replacements' => ...])`
   - ✅ Now: `load(['customer', 'serviceType'])`

3. **show()** - Line 48
   - ❌ Was: `load(['customer', 'serviceType', 'replacements' => ...])`
   - ✅ Now: `load(['customer', 'serviceType'])`

4. **update()** - Line 61
   - ❌ Was: `load(['customer', 'serviceType', 'replacements' => ...])`
   - ✅ Now: `load(['customer', 'serviceType'])`

5. **apiList()** - Line 113
   - ❌ Was: `Service::with(['customer', 'serviceType', 'replacements' => ...])`
   - ✅ Now: `Service::with(['customer', 'serviceType'])`

## What Changed
**Before:**
```php
public function apiList(Request $request)
{
    $query = Service::with(['customer', 'serviceType', 'replacements' => function ($q) {
        $q->where('is_disabled', 0);
    }]);
    // ...
}
```

**After:**
```php
public function apiList(Request $request)
{
    // Load only customer and serviceType relationships
    // Don't load replacements to avoid table-not-found errors
    $query = Service::with(['customer', 'serviceType']);
    // ...
}
```

## Result
✅ Services list now loads without errors
✅ Can create, read, update services
✅ Form populates with customer and service type data
✅ No HTTP 500 errors

## Note About Service Replacements
- The `service_replacements` table migration exists but hasn't been created in the database
- To enable replacements feature in the future:
  1. Run migration: `php artisan migrate`
  2. Then uncomment the `replacements` loading in controller methods

## Testing Steps
1. Open F12 Console
2. Navigate to `/Service` page
3. Should see: ✅ Services loaded: {count: X}
4. No ❌ Error messages should appear
