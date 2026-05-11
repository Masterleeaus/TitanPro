<?php

namespace Modules\TitanEchoAssist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Conversation extends Model
{
    protected $table = 'ext_chatbot_conversations';

    protected $fillable = [
        'chatbot_id',
        'session_id',
        'conversation_name',
        'ip_address',
        'connect_agent_at',
        'last_activity_at',
        'customer_payload',
        'chatbot_channel',
        'chatbot_channel_id',
        'customer_channel_id',
        'customer_id',
        'company_id',
        'ticket_status',
        'country_code',
        'chatbot_customer_id',
        'pinned',
        'send_email_at',
        'is_showed_on_history',
    ];

    protected $casts = [
        'customer_payload'  => 'array',
        'connect_agent_at'  => 'datetime',
        'last_activity_at'  => 'datetime',
        'send_email_at'     => 'datetime',
        'pinned'            => 'boolean',
        'is_showed_on_history' => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (self $conversation): void {
            if (
                $conversation->chatbot_channel_id === null
                || ! Schema::hasTable('tz_portal_operator_channels')
                || ! Schema::hasTable('tz_portal_operator_conversations')
            ) {
                return;
            }

            $operatorId = DB::table('tz_portal_operator_channels')
                ->where('id', $conversation->chatbot_channel_id)
                ->value('operator_id');

            if (! is_numeric($operatorId)) {
                return;
            }

            DB::table('tz_portal_operator_conversations')->updateOrInsert(
                [
                    'operator_id' => (int) $operatorId,
                    'session_id' => $conversation->session_id,
                ],
                [
                    'chatbot_channel_id' => $conversation->chatbot_channel_id,
                    'last_activity_at' => $conversation->last_activity_at ?? now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        });
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotHistory::class, 'conversation_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ChatbotCustomer::class, 'chatbot_customer_id');
    }
}
