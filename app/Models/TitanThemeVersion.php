<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanThemeVersion extends Model
{
    public const MAX_VERSIONS_PER_PANEL = 50;

    public $timestamps = false;

    protected $fillable = [
        'org_id',
        'panel',
        'version_number',
        'token_snapshot',
        'label',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'token_snapshot' => 'array',
        'created_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function createSnapshot(
        int $orgId,
        string $panel,
        array $tokenSnapshot,
        ?string $label = null,
        ?int $createdBy = null
    ): self {
        $nextVersion = ((int) static::query()
            ->where('org_id', $orgId)
            ->where('panel', $panel)
            ->max('version_number')) + 1;

        $version = static::query()->create([
            'org_id' => $orgId,
            'panel' => $panel,
            'version_number' => $nextVersion,
            'token_snapshot' => $tokenSnapshot,
            'label' => trim((string) $label) !== '' ? trim((string) $label) : "v{$nextVersion}",
            'created_by' => $createdBy,
            'created_at' => now(),
        ]);

        static::pruneForPanel($orgId, $panel);

        return $version;
    }

    public static function pruneForPanel(int $orgId, string $panel): void
    {
        $idsToDelete = static::query()
            ->where('org_id', $orgId)
            ->where('panel', $panel)
            ->orderByDesc('version_number')
            ->skip(self::MAX_VERSIONS_PER_PANEL)
            ->pluck('id');

        if ($idsToDelete->isNotEmpty()) {
            static::query()->whereIn('id', $idsToDelete)->delete();
        }
    }
}
