<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Stores per-component CSS overrides applied via the Visual UI Inspector.
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property int|null    $organization_id
 * @property string      $component_key
 * @property array       $properties
 */
class UiOverride extends Model implements TenantAware
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'organization_id',
        'component_key',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Upsert properties for a given component key.
     *
     * @param  string  $componentKey
     * @param  array<string,string>  $properties
     * @param  int|null  $organizationId
     * @param  int|null  $userId
     */
    public static function upsertForComponent(
        string $componentKey,
        array $properties,
        ?int $organizationId,
        ?int $userId
    ): self {
        return static::updateOrCreate(
            ['component_key' => $componentKey, 'organization_id' => $organizationId],
            ['user_id' => $userId, 'properties' => $properties],
        );
    }

    /**
     * Fetch all overrides for the given organization as an associative array
     * keyed by component_key → properties.
     *
     * @param  int|null  $organizationId
     * @return array<string, array<string, string>>
     */
    public static function allForOrg(?int $organizationId): array
    {
        return static::where('organization_id', $organizationId)
            ->get()
            ->keyBy('component_key')
            ->map(fn (self $r) => $r->properties)
            ->all();
    }

    /**
     * Delete the override for a specific component.
     */
    public static function resetComponent(string $componentKey, ?int $organizationId): void
    {
        static::where('component_key', $componentKey)
            ->where('organization_id', $organizationId)
            ->delete();
    }
}
