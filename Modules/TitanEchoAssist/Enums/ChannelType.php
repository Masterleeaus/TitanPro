<?php
namespace Modules\TitanEchoAssist\Enums;

enum ChannelType: string
{
    case Website = 'website';
    case Whatsapp = 'whatsapp';
    case Telegram = 'telegram';
    case Messenger = 'messenger';
    case Voice = 'voice';
}
