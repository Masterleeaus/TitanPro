<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RoleUIProfile
 *
 * Stores per-role UI customisations (theme colours, hidden nav items, widget
 * layout) scoped to an organisation. When a user logs in, the middleware
 * resolves their primary role, loads any matching profile, and merges the
 * overrides on top of the platform defaults.
 *
 * @property int       $organization_id
 * @property string    $role
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $accent_color
 * @property string|null $surface_color
 * @property array|null  $hidden_nav_items  Array of nav-item keys to suppress.
 * @property array|null  $widget_layout     Ordered widget-type array for the dashboard.
 */
class RoleUIProfile extends Model implements TenantAware
{
    use BelongsToTenant;

    /** Regex matching valid CSS hex colours: #rgb or #rrggbb. */
    private const HEX_COLOR_REGEX = '/^#[0-9a-fA-F]{3}(?:[0-9a-fA-F]{3})?$/';

    protected $fillable = [
        'organization_id',
        'role',
        'primary_color',
        'secondary_color',
        'accent_color',
        'surface_color',
        'hidden_nav_items',
        'widget_layout',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'hidden_nav_items' => 'array',
        'widget_layout'    => 'array',
    ];

    // ── Roles that may have a profile ──────────────────────────────────────────

    /**
     * Canonical role slugs the UI studio exposes (slug => label).
     *
     * @var array<string, string>
     */
    public const SUPPORTED_ROLES = [
        'admin'       => 'Admin',
        'owner'       => 'Owner',
        'dispatcher'  => 'Dispatcher',
        'bookkeeper'  => 'Bookkeeper / Finance',
        'technician'  => 'Technician (Mobile)',
    ];

    /**
     * Role priority order used when a user holds multiple roles.
     * The first match in this list wins.
     *
     * @var list<string>
     */
    private const ROLE_PRIORITY = ['admin', 'owner', 'dispatcher', 'bookkeeper', 'technician'];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // ── Query helpers ──────────────────────────────────────────────────────────

    /**
     * Find the profile for a given role within an organisation, bypassing the
     * global TenantScope so callers may pass an explicit organisation ID.
     */
    public static function forRole(string $role, int $organizationId): ?static
    {
        return static::withoutGlobalScope(TenantScope::class)
            ->where('organization_id', $organizationId)
            ->where('role', $role)
            ->first();
    }

    /**
     * Resolve the single "primary" role for a user deterministically.
     *
     * Users may hold multiple roles. Rather than relying on the non-deterministic
     * ordering of Spatie's `getRoleNames()`, we pick the first role that appears
     * in ROLE_PRIORITY. Falls back to the user's first assigned role if none
     * match the priority list (e.g. custom roles).
     *
     * @param  \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable $user
     */
    public static function resolvePrimaryRole(mixed $user): ?string
    {
        $userRoles = $user->getRoleNames()->all();

        foreach (self::ROLE_PRIORITY as $priorityRole) {
            if (in_array($priorityRole, $userRoles, true)) {
                return $priorityRole;
            }
        }

        return $userRoles[0] ?? null;
    }

    // ── Theme helpers ──────────────────────────────────────────────────────────

    /**
     * Return only the non-null colour overrides as an associative array.
     * Values are validated against the hex-colour pattern; invalid values are
     * silently dropped to prevent CSS injection.
     *
     * @return array<string, string>
     */
    public function themeOverrides(): array
    {
        $overrides = [];

        foreach (['primary_color', 'secondary_color', 'accent_color', 'surface_color'] as $field) {
            $value = $this->{$field};
            if ($value !== null && preg_match(self::HEX_COLOR_REGEX, $value)) {
                $overrides[$field] = $value;
            }
        }

        return $overrides;
    }
}
