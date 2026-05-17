<?php

namespace App\Support\TitanOS\Contracts;

/**
 * Modules can implement this interface to expose additional Titan Zero
 * context fields for AI interactions.  The titanZeroContext method should
 * return a key/value array of context data.  Domain specific data must be
 * kept generic and avoid exposing sensitive details.
 */
interface ProvidesTitanZeroContext
{
    /**
     * Return additional context as an associative array.
     *
     * @return array<string, mixed>
     */
    public static function titanZeroContext(): array;
}