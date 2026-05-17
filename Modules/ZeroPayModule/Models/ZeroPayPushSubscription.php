<?php

namespace Modules\ZeroPayModule\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZeroPayPushSubscription extends Model
{
    protected $table = 'zeropay_push_subscriptions';

    protected $fillable = [
        'user_id',
        'company_id',
        'endpoint',
        'p256dh_key',
        'auth_key',
        'content_encoding',
    ];

    /**
     * Find or create a subscription record from the JSON data sent by the browser.
     *
     * @param  array<string,mixed>  $subscriptionJson
     */
    public static function upsertFromJson(int $userId, ?int $companyId, array $subscriptionJson): static
    {
        $keys = $subscriptionJson['keys'] ?? [];

        return static::updateOrCreate(
            ['endpoint' => $subscriptionJson['endpoint']],
            [
                'user_id' => $userId,
                'company_id' => $companyId,
                'p256dh_key' => $keys['p256dh'] ?? '',
                'auth_key' => $keys['auth'] ?? '',
                'content_encoding' => $subscriptionJson['contentEncoding'] ?? 'aesgcm',
            ]
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class));
    }
}
