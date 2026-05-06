<?php
namespace Modules\TitanNexus\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;use Modules\TitanNexus\Models\LeadRecord;use Modules\TitanNexus\Policies\LeadRecordPolicy;
class AuthServiceProvider extends ServiceProvider{protected $policies=[LeadRecord::class=>LeadRecordPolicy::class];}
