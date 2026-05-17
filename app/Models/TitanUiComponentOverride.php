<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores per-component design-token overrides and named presets.
 *
 * Active overrides have preset_name = null.
 * Named presets have preset_name set and are not applied automatically.
 *
 * @property int         $id
 * @property string      $component
 * @property string|null $panel
 * @property int|null    $organization_id
 * @property string      $token_key
 * @property string      $value
 * @property string|null $preset_name
 */
class TitanUiComponentOverride extends Model implements TenantAware
{
    use BelongsToTenant;
    protected $table = 'titan_ui_component_overrides';

    protected $fillable = [
        'component',
        'panel',
        'organization_id',
        'token_key',
        'value',
        'preset_name',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    /** Active overrides only (no preset name). */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('preset_name');
    }

    /** Rows belonging to a specific named preset. */
    public function scopePreset(Builder $query, string $name): Builder
    {
        return $query->where('preset_name', $name);
    }

    /** Filter by component key. */
    public function scopeForComponent(Builder $query, string $component): Builder
    {
        return $query->where('component', $component);
    }

    /** Filter by panel id. Passing null matches rows where panel IS NULL (platform-wide). */
    public function scopeForPanel(Builder $query, ?string $panel): Builder
    {
        return $panel === null
            ? $query->whereNull('panel')
            : $query->where('panel', $panel);
    }

    // ── Class-level helpers ───────────────────────────────────────────────────

    /**
     * Load all active token values for a component + panel combination.
     * Returns an array keyed by token_key.
     *
     * @return array<string, string>
     */
    public static function loadTokens(string $component, ?string $panel): array
    {
        return static::query()
            ->active()
            ->forComponent($component)
            ->forPanel($panel)
            ->pluck('value', 'token_key')
            ->all();
    }

    /**
     * Persist (upsert) a set of token values as active overrides.
     *
     * @param array<string, string> $tokens   token_key => value
     */
    public static function saveTokens(string $component, ?string $panel, array $tokens): void
    {
        $organizationId = auth()->user()?->organization_id;

        foreach ($tokens as $tokenKey => $value) {
            static::updateOrCreate(
                [
                    'component'       => $component,
                    'panel'           => $panel,
                    'organization_id' => $organizationId,
                    'token_key'       => $tokenKey,
                    'preset_name'     => null,
                ],
                ['value' => $value]
            );
        }
    }

    /**
     * Save the current active overrides for a component as a named preset.
     * Existing rows for this preset name are deleted first (full replace).
     */
    public static function savePreset(string $component, ?string $panel, string $presetName, array $tokens): void
    {
        $organizationId = auth()->user()?->organization_id;

        // Clear any existing rows for this preset + component + panel.
        static::query()
            ->preset($presetName)
            ->forComponent($component)
            ->forPanel($panel)
            ->delete();

        foreach ($tokens as $tokenKey => $value) {
            static::create([
                'component'       => $component,
                'panel'           => $panel,
                'organization_id' => $organizationId,
                'token_key'       => $tokenKey,
                'preset_name'     => $presetName,
                'value'           => $value,
            ]);
        }
    }

    /**
     * Apply a named preset to the active overrides for a component + panel.
     * Existing active overrides for that component are removed first.
     */
    public static function applyPreset(string $component, ?string $panel, string $presetName): void
    {
        $presetTokens = static::query()
            ->preset($presetName)
            ->forComponent($component)
            ->forPanel($panel)
            ->pluck('value', 'token_key')
            ->all();

        // Clear active overrides.
        static::query()
            ->active()
            ->forComponent($component)
            ->forPanel($panel)
            ->delete();

        // Write preset values as new active overrides.
        static::saveTokens($component, $panel, $presetTokens);
    }

    /**
     * Clear all active overrides for a component + panel (reset to theme defaults).
     */
    public static function resetOverrides(string $component, ?string $panel): void
    {
        static::query()
            ->active()
            ->forComponent($component)
            ->forPanel($panel)
            ->delete();
    }

    /**
     * Return the distinct preset names available for a component + panel.
     *
     * @return array<string>
     */
    public static function presetNames(string $component, ?string $panel): array
    {
        return static::query()
            ->forComponent($component)
            ->forPanel($panel)
            ->whereNotNull('preset_name')
            ->distinct()
            ->pluck('preset_name')
            ->all();
    }
}
