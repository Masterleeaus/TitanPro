<?php

namespace App\Support\TitanOS\Context;

/**
 * A simple immutable container for OS context fields that can be passed to
 * views or returned as JSON.  Only generic metadata is included here –
 * domain specific data (dispatch records, payroll info etc.) is intentionally
 * excluded to keep the Business OS shell generic and safe.
 */
class OsContext
{
    public function __construct(
        public readonly string $panel_id = '',
        public readonly string $panel_path = '',
        public readonly string $route_name = '',
        public readonly string $route_path = '',
        public readonly string $page_title = '',
        public readonly string $user_id = '',
        public readonly string $user_role = '',
        public readonly string $company_id = '',
        public readonly string $app_key = '',
        public readonly string $shell_mode = '',
    ) {
    }
}