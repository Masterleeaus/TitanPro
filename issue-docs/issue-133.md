# Issue 133

## Issue Summary

Both `InvoiceLineItem` and `JobLineItem` included `'total' => 'decimal:2'` in their `$casts` array. The `total` column is a **database-generated stored column** (`storedAs('unit_price * quantity')`), meaning the database computes it automatically and it cannot be written to. If `total` were ever included in a `create()` or `update()` call via mass assignment, the database would reject the query with an error.

## Root Cause

The `total` cast was added alongside `unit_price` and `quantity` casts to ensure the value was returned as a decimal, which is fine for reads. However, having `total` in `$casts` implies the model treats it as a writable attribute, and could allow it to slip into a mass-assignment payload if code is not careful. The database will reject any attempt to write to a stored generated column.

## Changes Made

### `app/Models/InvoiceLineItem.php`

Removed `'total' => 'decimal:2'` from the `casts()` method.

**Before:**
```php
protected function casts(): array
{
    return [
        'unit_price' => 'decimal:2',
        'quantity'   => 'decimal:3',
        'total'      => 'decimal:2', // ← generated column, cannot be written
        'is_taxable' => 'boolean',
    ];
}
```

**After:**
```php
protected function casts(): array
{
    return [
        'unit_price' => 'decimal:2',
        'quantity'   => 'decimal:3',
        'is_taxable' => 'boolean',
    ];
}
```

`total` is already absent from `$fillable` in this model, so no mass-assignment change was required.

### `app/Models/JobLineItem.php`

Removed `'total' => 'decimal:2'` from the `casts()` method.

**Before:**
```php
protected function casts(): array
{
    return [
        'unit_price' => 'decimal:2',
        'quantity'   => 'decimal:3',
        'total'      => 'decimal:2', // ← generated column, cannot be written
    ];
}
```

**After:**
```php
protected function casts(): array
{
    return [
        'unit_price' => 'decimal:2',
        'quantity'   => 'decimal:3',
    ];
}
```

`total` is already absent from `$fillable` in this model, so no mass-assignment change was required.

## Tests Added or Updated

No new tests were added. The fix is a removal of an incorrect cast entry. Existing feature tests covering invoice and job line item creation continue to pass. The `total` value is still accessible as a plain attribute on the model (returned by the DB as a decimal string) and can be read normally — it just is no longer cast through Eloquent's cast pipeline.

## Next Steps

- If any consumer code explicitly accesses `$lineItem->total` and relies on it being a PHP `string` formatted to 2 decimal places, wrap those reads with `number_format()` or `bcscale()` as needed, since the value will now be returned as a plain string from the DB driver (typically already correctly formatted to 2dp by the stored-column definition).
- Consider adding a `getComputedColumns()` helper or a doc-block comment on both models indicating that `total` is a DB-generated read-only column, to prevent this regression in the future.
