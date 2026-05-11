<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Http\Resources\Admin;

use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorHistoryResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class TitanOperatorConversationResource extends JsonResource
{
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        return [
            'id'                => $this->getAttribute('id'),
            'session_id'        => $this->getAttribute('session_id'),
            'operator_channel'   => $this->getAttribute('operator_channel'),
            'color'             => Arr::random(['#879EC4', '#018a1a', '#7f00c8', '#e633ec']),
            'ip_address'        => $this->getAttribute('ip_address'),
            'conversation_name' => $this?->customer?->name ?: $this->getAttribute('conversation_name'),
            'customer'          => $this->customer,
            'titan_operator'           => TitanOperatorResource::make($this->getAttribute('titan_operator')),
            'lastMessage'       => $this->getAttribute('lastMessage') ? TitanOperatorHistoryResource::make($this->getAttribute('lastMessage')) : [
                'message' => 'No message',
                'read_at' => now(),
            ],
            'ticket_status'      => $this->getAttribute('ticket_status'),
            'country_code'       => $this->getAttribute('country_code'),
            'pinned'             => $this->getAttribute('pinned'),
            'operator_id'         => $this->getAttribute('operator_id'),
            'created_at'         => $this->getAttribute('created_at'),
            'histories'          => TitanOperatorHistoryResource::collection($this->getAttribute('histories')),
        ];
    }
}
