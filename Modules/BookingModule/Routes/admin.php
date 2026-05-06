<?php

use Illuminate\Support\Facades\Route;

// Reserved for panel/admin-only BookingModule routes.
// Legacy admin routes are currently defined in Routes/web.php.
Route::middleware(['web', 'auth'])->group(function () {
    // Intentionally empty: kept for Titan blueprint discovery.
});
