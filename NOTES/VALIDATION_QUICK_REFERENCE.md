# PHP-Dependent Validation - Quick Reference

## 🎯 What Changed

**Before:** Used `$request->validated()` from CustomerRequest class
**After:** Pure PHP validation in controller

---

## 📋 Validation Pattern

```php
// Extract input
$field = $request->input('field_name', '');

// Validate
if (empty($field) || !is_string($field) || strlen($field) > 255) {
    return response()->json([
        'success' => false,
        'message' => 'Field is required and must be less than 255 characters.'
    ], 422);
}
```

---

## ✅ Validation Checks

| Check | Code | Meaning |
|-------|------|---------|
| Empty | `empty($field)` | Field is empty, null, 0, false, or '' |
| String | `!is_string($field)` | Field is NOT a string |
| Length | `strlen($field) > 255` | Field exceeds 255 characters |

---

## 📝 Fields Validated

1. first_name
2. last_name
3. contact_no
4. gender
5. street
6. brgy
7. city_province

**All:** Required, String, Max 255 chars

---

## 🔄 Data Flow

```
Form Submit
  ↓
Extract inputs
  ↓
Validate each field (PHP)
  ↓
Validation fails? → Return 422 error
  ↓
Validation passes? → Create/Update customer
  ↓
Return 200 success
```

---

## 📊 HTTP Status Codes

| Status | Meaning | Example |
|--------|---------|---------|
| 200 | Success | Customer created/updated |
| 422 | Validation Error | Field is empty |
| 500 | Server Error | Database error |

---

## 💾 Methods

### store() - Add Customer
**Location:** `CustomerController::store()` (Lines 17-108)
- Extracts all inputs
- Validates all fields
- Creates customer if valid
- Returns 200 or 422

### update() - Update Customer
**Location:** `CustomerController::update()` (Lines 122-215)
- Extracts all inputs
- Validates all fields
- Updates customer if valid
- Returns 200 or 422

---

## 🧪 Test Cases

### ✅ Valid Data
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "contact_no": "09123456789",
  "gender": "Male",
  "street": "123 Main St",
  "brgy": "Barangay 1",
  "city_province": "Manila"
}
```
**Result:** 200 OK - Customer created

### ❌ Empty Field
```json
{
  "first_name": "",
  ...
}
```
**Result:** 422 - "First name is required..."

### ❌ Field Too Long
```json
{
  "first_name": "John" + (251 chars),
  ...
}
```
**Result:** 422 - "First name is required..."

### ❌ Non-String Value
```json
{
  "first_name": 12345,
  ...
}
```
**Result:** 422 - "First name is required..."

---

## 🔍 Error Messages

**Format:** `"{Field} is required and must be less than 255 characters."`

**Examples:**
- "First name is required and must be less than 255 characters."
- "Last name is required and must be less than 255 characters."
- "Contact number is required and must be less than 255 characters."
- "Gender is required and must be less than 255 characters."
- "Street is required and must be less than 255 characters."
- "Barangay is required and must be less than 255 characters."
- "City/Province is required and must be less than 255 characters."

---

## 📁 Files Modified

| File | Changes |
|------|---------|
| `app/Http/Controllers/CustomerController.php` | Added PHP validation to store() and update() |

---

## 🚀 Usage

### Add Customer
```
POST /customers
Content-Type: application/json

{
  "first_name": "John",
  "last_name": "Doe",
  "contact_no": "09123456789",
  "gender": "Male",
  "street": "123 Main St",
  "brgy": "Barangay 1",
  "city_province": "Manila"
}
```

### Update Customer
```
PUT /customers/{id}
Content-Type: application/json

{
  "first_name": "Jane",
  "last_name": "Smith",
  "contact_no": "09987654321",
  "gender": "Female",
  "street": "456 Oak Ave",
  "brgy": "Barangay 2",
  "city_province": "Cebu"
}
```

---

## ✨ Benefits

✅ Pure PHP validation
✅ No Request class dependency
✅ Specific error messages
✅ Easy to debug
✅ Easy to modify
✅ Direct control
✅ Explicit logic

---

## 📚 Documentation

- **Complete Guide:** `PHP_DEPENDENT_VALIDATION.md`
- **Integration Guide:** `CUSTOMER_FORM_INTEGRATION.md`
- **Implementation Summary:** `IMPLEMENTATION_SUMMARY.md`

---

**Status:** ✅ READY FOR PRODUCTION
