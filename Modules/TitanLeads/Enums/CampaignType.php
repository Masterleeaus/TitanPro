<?php

namespace Modules\TitanLeads\Enums;

enum CampaignType: string
{
    case telegram  = 'telegram';
    case whatsapp  = 'whatsapp';
    case sms       = 'sms';
    case messenger = 'messenger';
    case voice     = 'voice';
    case email     = 'email';
}
