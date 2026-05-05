<?php

use Illuminate\Support\Facades\Broadcast;

// Define any broadcasting channels for the CallingAgent module.
Broadcast::channel('CallingAgent.{id}', function ($user, $id) {
    // Authorize the user to listen to this channel.
    // Return true to allow, or perform additional checks as needed.
    return true;
});