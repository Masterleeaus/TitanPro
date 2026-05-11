<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Persisted record of an AI-generated theme design system.
 *
 * Each row represents one generation event. Active themes are applied
 * back to OrganizationBranding / PlatformSetting via UiStudio::acceptAiTheme().
 *
 * @property int         $id
 * @property int|null    $organization_id
 * @property int|null    $user_id
 * @property string      $name
 * @property string      $prompt
 * @property array       $tokens
 */
class AiThemeSnapshot extends Model implements TenantAware
{
    use BelongsToTenant;

    protected $fillable = [
        'organization_id',
        'user_id',
        'name',
        'prompt',
        'tokens',
    ];

    protected $casts = [
        'tokens' => 'array',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Class-level helpers ───────────────────────────────────────────────────

    /**
     * Count how many AI theme generations have been made today for the given org.
     * Returns 0 when $orgId is null (unauthenticated / global context).
     */
    public static function todayCountForOrg(?int $orgId): int
    {
        if ($orgId === null) {
            return 0;
        }

        return static::query()
            ->where('organization_id', $orgId)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Persist a snapshot for an AI generation event and return it.
     *
     * The snapshot name is auto-generated from the prompt and date unless
     * a custom $label is provided.
     */
    public static function createFromGeneration(
        ?int $orgId,
        ?int $userId,
        string $prompt,
        array $tokens,
        string $label = ''
    ): static {
        $date = now()->format('Y-m-d');
        // Build a short title from the prompt (max 60 chars) then append the date.
        $shortPrompt = mb_substr(trim($prompt), 0, 60);
        $name = $label !== '' ? $label : "AI: {$shortPrompt} — {$date}";

        return static::create([
            'organization_id' => $orgId,
            'user_id'         => $userId,
            'name'            => $name,
            'prompt'          => $prompt,
            'tokens'          => $tokens,
        ]);
    }
}
