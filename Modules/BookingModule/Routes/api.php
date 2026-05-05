<?php

// Blueprint compatibility route wrapper.
// Primary API routes live in Routes/api/v1/api.php and are loaded by RouteServiceProvider.
$apiV1 = __DIR__ . '/api/v1/api.php';
if (file_exists($apiV1)) {
    require $apiV1;
}
