# POS Sale Logging - Testing Guide

**Status:** ✅ READY FOR TESTING

---

## Quick Verification Checklist

### Pre-Test (Before Running POS)

- [ ] Database tables exist
  ```sql
  SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'computershop_inventory' AND table_name = 'auditlogs';
  ```
  Expected: `1` (table exists)

- [ ] Staff user is authenticated
- [ ] Customer exists in database
- [ ] At least 2 products available for sale

---

## Step-by-Step Test

### Test 1: Simple Single Item Sale

**Steps:**

1. Open POS system
2. Click "New Sale"
3. Select any customer (e.g., "John Doe")
4. Scan/select 1 product
5. Click "Checkout"
6. Select payment method (Cash/Card/Check)
7. Click "Process Purchase"
8. Verify receipt appears

**Expected Result:**
- Receipt shows successfully
- No errors in browser console

**Verify Audit Log:**
```sql
SELECT * FROM auditlogs 
WHERE module = 'POS' AND action = 'Sold'
ORDER BY created_at DESC 
LIMIT 1;
```

Expected output:
```
user_id: 2 (staff member)
action: "Sold"
module: "POS"
description: "Sold 1 items to John Doe (Total: 1500)"
changes: {
  "customer_id": 1,
  "customer_name": "John Doe",
  "quantity": 1,
  "total_price": 1500
}
ip_address: (not null)
created_at: (current timestamp)
```

---

### Test 2: Multiple Items Same Product

**Steps:**

1. Open POS system
2. Select customer
3. Scan same product multiple times (qty: 3)
4. Click "Checkout"
5. Select payment method
6. Click "Process Purchase"
7. Verify receipt

**Expected Result:**
- Receipt shows 3 items total
- Audit log quantity = 3

**Verify Audit Log:**
```sql
SELECT description FROM auditlogs 
WHERE module = 'POS' 
ORDER BY created_at DESC 
LIMIT 1;
```

Expected: `"Sold 3 items to {customer_name} (Total: {price})"`

---

### Test 3: Multiple Items Different Products

**Steps:**

1. Open POS system
2. Select customer
3. Scan product 1 (qty: 1)
4. Scan product 2 (qty: 2)
5. Click "Checkout"
6. Select payment method
7. Click "Process Purchase"
8. Verify receipt

**Expected Result:**
- Receipt shows 3 items total (1 + 2)
- Audit log quantity = 3

**Verify Audit Log:**
```sql
SELECT changes FROM auditlogs 
WHERE module = 'POS' AND description LIKE '%3 items%'
ORDER BY created_at DESC 
LIMIT 1;
```

Expected: `quantity: 3`

---

### Test 4: Different Customers

**Steps:**

1. Repeat Test 1 with Customer A
2. Repeat Test 1 with Customer B
3. Repeat Test 1 with Customer C

**Expected Result:**
- 3 separate audit entries
- Each with different customer_name

**Verify Audit Log:**
```sql
SELECT description, changes FROM auditlogs 
WHERE module = 'POS' 
ORDER BY created_at DESC 
LIMIT 3;
```

Expected:
```
Row 1: "Sold X items to Customer C (Total: Y)"
Row 2: "Sold X items to Customer B (Total: Y)"
Row 3: "Sold X items to Customer A (Total: Y)"
```

---

### Test 5: Verify Data Accuracy

**Steps:**

1. In POS, note down:
   - Customer name
   - Item quantity
   - Total price
2. Process checkout
3. Query audit log

**Expected Result:**
- Description matches exactly
- Changes JSON has correct values

**Verify Audit Log:**
```sql
SELECT 
  description,
  JSON_EXTRACT(changes, '$.customer_name') as customer_name,
  JSON_EXTRACT(changes, '$.quantity') as quantity,
  JSON_EXTRACT(changes, '$.total_price') as total_price
FROM auditlogs 
WHERE module = 'POS'
ORDER BY created_at DESC 
LIMIT 1;
```

---

## Troubleshooting

### Issue: Audit log not created

**Check 1: Is the sale completing?**
```sql
SELECT COUNT(*) FROM customer_purchase_orders 
WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR);
```
If count = 0, sale is not being processed.

**Check 2: Is the logging method being called?**
- Look in `storage/logs/laravel.log` for errors
- Search for: `Failed to log audit trail`

**Check 3: Does AuditLog table exist?**
```sql
DESC auditlogs;
```
If error, create table via migration.

**Check 4: Is stored procedure present?**
```sql
SHOW PROCEDURE STATUS WHERE Name = 'sp_insert_audit_log';
```
If no results, SP needs to be created.

---

### Issue: Description format incorrect

**Expected:** `"Sold 3 items to John Doe (Total: 1500)"`
**Actual:** Different format

**Check:**
1. Customer name has correct format (first + space + last)
2. Quantity is calculated correctly
3. Total price is correct

**Debug:**
```sql
SELECT 
  description,
  changes
FROM auditlogs 
WHERE module = 'POS'
ORDER BY created_at DESC 
LIMIT 1;
```

---

### Issue: Quantity wrong

**Expected:** 3 items
**Actual:** Different quantity

**Debug:**
```php
// In CheckoutController, add temporary logging
Log::info('Items received:', $items);
Log::info('Total quantity: ', [
    'total' => collect($items)->sum('quantity')
]);
```

---

## Performance Monitoring

### Query Audit Log Performance

```sql
-- Count audit entries by module
SELECT module, COUNT(*) as count
FROM auditlogs
GROUP BY module;

-- Average response time for logging
SELECT 
  AVG(TIMESTAMPDIFF(MICROSECOND, created_at, updated_at)) as avg_microseconds
FROM auditlogs
WHERE module = 'POS';

-- Most recent 10 POS sales
SELECT 
  u.first_name,
  u.last_name,
  a.description,
  a.created_at
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'POS'
ORDER BY a.created_at DESC
LIMIT 10;
```

---

## Success Criteria

✅ **All tests pass if:**

1. **Sale completes** - Receipt appears without error
2. **Audit log created** - Entry appears in auditlogs table
3. **Action correct** - action = "Sold"
4. **Module correct** - module = "POS"
5. **Description format** - "Sold X items to {name} (Total: Y)"
6. **User tracked** - user_id matches logged-in staff
7. **Customer accurate** - customer_name matches
8. **Quantity correct** - quantity matches items sum
9. **Price correct** - total_price matches amount
10. **IP captured** - ip_address is not null

---

## Next Steps After Testing

### If All Tests Pass ✅
- [ ] Update documentation
- [ ] Communicate to team
- [ ] Monitor production usage
- [ ] Create sales analytics reports

### If Tests Fail ❌
- [ ] Check laravel.log for errors
- [ ] Verify database connectivity
- [ ] Check stored procedure exists
- [ ] Verify user authentication
- [ ] Check IP address capture

---

## Rollback (If Needed)

If critical issues found:

1. Revert CheckoutController changes
2. Remove LogsAuditTrail import
3. Remove logSaleAudit() call
4. Redeploy

---

**Testing Status:** Ready to begin
**Estimated Time:** 15 minutes
**Resources Needed:** None (uses existing database)
