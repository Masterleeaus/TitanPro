<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchRoute extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'dispatch_routes';

    protected $fillable = ['company_id', 'technician_id', 'route_date', 'name', 'status', 'total_distance_meters', 'total_duration_seconds', 'started_at', 'completed_at', 'metadata'];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'route_date' => 'date', 'started_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function technician()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'technician_id');
    }

    public function stops()
    {
        return $this->hasMany(DispatchRouteStop::class, 'dispatch_route_id')->orderBy('sequence');
    }
}
