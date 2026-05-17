<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Models\PropertyConnect;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\TitanLeads\System\Models\Whatsapp\Contact;
use App\Extensions\TitanLeads\System\Models\MarketingConversation;

class PcLead extends Model
{
    protected $table = 'ext_pc_leads';

    protected $fillable = [
        'user_id',
        'company_id',
        'contact_id',
        'pipeline_id',
        'stage_id',
        'lead_type',
        'company_name',
        'person_name',
        'email',
        'phone',
        'source',
        'notes',
        'last_contacted_at',
        'next_followup_at',
        'is_active',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'next_followup_at'  => 'datetime',
        'is_active'         => 'boolean',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function conversations()
    {
        return $this->hasMany(MarketingConversation::class, 'lead_id');
    }
}
