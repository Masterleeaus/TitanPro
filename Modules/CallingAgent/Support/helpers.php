<?php

// Global helper functions for the CallingAgent module.

if (! function_exists('calling_agent_name')) {
    /**
     * Example helper returning the module's human-readable name.
     *
     * @return string
     */
    function calling_agent_name(): string
    {
        return 'Calling Agent';
    }
}