<?php

namespace App\Extensions\TitanCommand\System\Enums;

enum PlatformEnum: string
{
    case unknown = 'unknown';
    case website = 'website';
    case sms = 'sms';
    case whatsapp = 'whatsapp';
    case email = 'email';
    case facebook = 'facebook';
    case instagram = 'instagram';
    case tiktok = 'tiktok';
    case google = 'google';
}
