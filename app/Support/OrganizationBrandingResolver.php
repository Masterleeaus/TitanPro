<?php

namespace App\Support;

use App\Models\OrganizationBranding;
use App\Models\PlatformSetting;

class OrganizationBrandingResolver
{
    public function current(): array
    {
        $platform = PlatformSetting::current();
        $organizationId = auth()->user()?->organization_id;

        if (! $organizationId) {
            return $this->platformDefaults($platform);
        }

        $branding = OrganizationBranding::query()
            ->where('organization_id', $organizationId)
            ->first();

        if (! $branding) {
            return $this->platformDefaults($platform);
        }

        $defaults = $this->platformDefaults($platform);

        return [
            'panel_name' => $branding->panel_name ?: $defaults['panel_name'],
            'logo_url' => $branding->logoUrl() ?: $defaults['logo_url'],
            'favicon_url' => $branding->faviconUrl() ?: $defaults['favicon_url'],
            'primary_color' => $branding->primary_color ?: $defaults['primary_color'],
            'secondary_color' => $branding->secondary_color ?: $defaults['secondary_color'],
            'font_family' => $branding->font_family ?: $defaults['font_family'],
            'background_type' => $branding->background_type ?: $defaults['background_type'],
            'background_value' => $branding->background_value ?: $defaults['background_value'],
            'menu_items' => $branding->menu_items ?: [],
            'dashboard_layout' => $branding->dashboard_layout ?: [],
        ];
    }

    public function panelName(string $fallback): string
    {
        return $this->current()['panel_name'] ?? $fallback;
    }

    public function primaryColor(string $fallback): string
    {
        return $this->current()['primary_color'] ?? $fallback;
    }

    protected function platformDefaults(PlatformSetting $platform): array
    {
        return [
            'panel_name' => $platform->brandName(),
            'logo_url' => $platform->logoUrl(),
            'favicon_url' => $platform->faviconUrl(),
            'primary_color' => $platform->primary_color ?: '#2563eb',
            'secondary_color' => $platform->secondary_color ?: '#0f172a',
            'font_family' => $platform->font_heading ?: 'Figtree',
            'background_type' => $platform->bg_image_path ? 'image' : 'none',
            'background_value' => $platform->bg_image_path,
            'menu_items' => [],
            'dashboard_layout' => [],
        ];
    }
}
