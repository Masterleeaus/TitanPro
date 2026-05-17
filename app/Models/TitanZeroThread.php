<?php

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Titan Zero conversation thread.
 *
 * Stores the full chat history and latest widget set for a single OS conversation.
 * Automatically scoped to the authenticated user's organisation via BelongsToTenant.
 */
class TitanZeroThread extends Model implements TenantAware
{
    use BelongsToTenant, HasFactory;

    protected $table = 'titan_zero_threads';

    protected $fillable = [
        'organization_id',
        'user_id',
        'app_key',
        'title',
        'messages',
        'widgets',
    ];

    protected $casts = [
        'messages' => 'array',
        'widgets'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Append a message to the thread's messages list and persist.
     *
     * @param  array{id: string, role: string, content: string, createdAt?: string}  $message
     */
    public function appendMessage(array $message): void
    {
        $messages   = $this->messages ?? [];
        $messages[] = $message;
        $this->messages = $messages;
    }
}
