<?php

namespace Modules\TitanEchoAssist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanOperator\Models\Channel as OperatorChannel;
use Modules\TitanOperator\Models\Conversation as OperatorConversation;

class Conversation extends Model
{
    protected $table = 'ext_chatbot_conversations';

    protected $fillable = [
        'chatbot_id',
        'session_id',
        'conversation_name',
        'email',
        'rating',
        'reviewed_at',
        'ip_address',
        'connect_agent_at',
        'last_activity_at',
        'internal_notes',
        'customer_payload',
        'chatbot_channel',
        'source_channel',
        'voice_call_duration',
        'voice_recording_url',
        'chatbot_channel_id',
        'customer_channel_id',
        'customer_id',
        'company_id',
        'ticket_status',
        'country_code',
        'chatbot_customer_id',
        'pinned',
        'is_pinned',
        'send_email_at',
        'is_showed_on_history',
    ];

    protected $casts = [
        'customer_payload'  => 'array',
        'rating'            => 'integer',
        'reviewed_at'       => 'datetime',
        'connect_agent_at'  => 'datetime',
        'last_activity_at'  => 'datetime',
        'voice_call_duration' => 'integer',
        'send_email_at'     => 'datetime',
        'pinned'            => 'boolean',
        'is_pinned'         => 'boolean',
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

            $channel = OperatorChannel::query()
                ->select(['operator_id', 'user_id'])
                ->find($conversation->chatbot_channel_id);

            $operatorId = filter_var($channel?->operator_id, FILTER_VALIDATE_INT);

            if ($channel === null || $operatorId === false) {
                return;
            }

            if ($conversation->company_id !== null) {
                if (! Schema::hasTable('users')) {
                    return;
                }

                $channelUserId = filter_var($channel->user_id, FILTER_VALIDATE_INT);

                if ($channelUserId === false) {
                    return;
                }

                $channelUserCompanyId = DB::table('users')
                    ->where('id', $channelUserId)
                    ->value('organization_id');

                $channelCompanyId = filter_var($channelUserCompanyId, FILTER_VALIDATE_INT);
                $conversationCompanyId = filter_var($conversation->company_id, FILTER_VALIDATE_INT);

                if (
                    $channelCompanyId === false
                    || $conversationCompanyId === false
                    || $channelCompanyId !== $conversationCompanyId
                ) {
                    return;
                }
            }

            OperatorConversation::query()->updateOrCreate(
                [
                    'operator_id' => $operatorId,
                    'session_id' => $conversation->session_id,
                ],
                [
                    'operator_channel_id' => $conversation->chatbot_channel_id,
                    'last_activity_at' => $conversation->last_activity_at ?? now(),
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
