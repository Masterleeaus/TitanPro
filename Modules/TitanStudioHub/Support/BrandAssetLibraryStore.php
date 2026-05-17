<?php

namespace Modules\TitanStudioHub\Support;

class BrandAssetLibraryStore
{
    /**
     * @var array<int, array<int, array<string, mixed>>>
     */
    private static array $assets = [];

    /**
     * @param  array<string, mixed>  $asset
     */
    public static function add(?int $companyId, array $asset): void
    {
        $companyId = $companyId ?? 0;
        self::$assets[$companyId] ??= [];
        self::$assets[$companyId][] = $asset;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(?int $companyId): array
    {
        return self::$assets[$companyId ?? 0] ?? [];
    }

    public static function flush(): void
    {
        self::$assets = [];
    }
}
