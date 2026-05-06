<?php
namespace Modules\TitanNexus\DTOs;
class JobAssistData { public function __construct(public mixed $tenant_id, public mixed $job_id, public mixed $customer_name, public mixed $site_address, public mixed $status, public mixed $next_action) {} }
