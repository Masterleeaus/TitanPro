<?php

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationBranding extends Model implements TenantAware
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'organization_id',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'font_family',
        'background_type',
        'background_value',
        'panel_name',
        'menu_items',
        'dashboard_layout',
        'installed_theme_packs',
    ];

    protected $casts = [
        'menu_items' => 'array',
        'dashboard_layout' => 'array',
        'installed_theme_packs' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function logoUrl(): ?string
    {
        return $this->publicUrl($this->logo_path);
    }

    public function faviconUrl(): ?string
    {
        return $this->publicUrl($this->favicon_path);
    }

    /** @return array<int, array<string, mixed>> */
    public function installedThemePacks(): array
    {
        $packs = $this->installed_theme_packs;

        if (! is_array($packs)) {
            return [];
        }

        return array_values(array_filter($packs, static fn (mixed $pack): bool => is_array($pack)));
    }

    /** @param  array<string, mixed>  $themePack */
    public function installThemePack(array $themePack): void
    {
        $slug = Str::slug((string) ($themePack['slug'] ?? $themePack['name'] ?? ''));

        if ($slug === '') {
            return;
        }

        $name = trim((string) ($themePack['name'] ?? ''));
        $author = trim((string) ($themePack['author'] ?? ''));
        $version = trim((string) ($themePack['version'] ?? ''));
        $tags = $themePack['tags'] ?? [];
        $tokens = $themePack['tokens'] ?? [];

        $normalizedPack = [
            'slug' => $slug,
            'name' => $name !== '' ? $name : Str::headline($slug),
            'author' => $author !== '' ? $author : 'Unknown',
            'version' => $version !== '' ? $version : '1.0.0',
            'tags' => is_array($tags) ? array_values(array_filter($tags, static fn (mixed $tag): bool => is_string($tag))) : [],
            'tokens' => is_array($tokens) ? $tokens : [],
            'installed_at' => now()->toIso8601String(),
        ];

        $packs = array_values(array_filter(
            $this->installedThemePacks(),
            static fn (array $pack): bool => ($pack['slug'] ?? null) !== $slug
        ));

        $packs[] = $normalizedPack;

        $this->forceFill(['installed_theme_packs' => $packs])->save();
    }

    public function uninstallThemePack(string $slug): void
    {
        $slug = Str::slug($slug);

        if ($slug === '') {
            return;
        }

        $packs = array_values(array_filter(
            $this->installedThemePacks(),
            static fn (array $pack): bool => ($pack['slug'] ?? null) !== $slug
        ));

        $this->forceFill(['installed_theme_packs' => $packs])->save();
    }

    protected function publicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
