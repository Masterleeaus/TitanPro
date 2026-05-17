<?php

namespace App\Extensions\TitanLeads\System\Models;

use App\Extensions\TitanLeads\System\Enums\EmbeddingTypeEnum;
use Illuminate\Database\Eloquent\Model;

class MarketingCampaignEmbedding extends Model
{
    protected $table = 'ext_marketing_campaign_embeddings';

    protected $fillable = [
        'marketing_campaign_id',
        'engine',
        'title',
        'file',
        'url',
        'content',
        'embedding',
        'type',
        'trained_at',
    ];

    protected $casts = [
        'embedding' => 'json',
        'type'      => EmbeddingTypeEnum::class,
    ];
}
