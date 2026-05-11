<?php

namespace App\Extensions\TitanOperator\System\Helpers;

use App\Helpers\Classes\MarketplaceHelper;

class TitanOperatorHelper
{
    public static function existChannels(): bool
    {
        return MarketplaceHelper::isRegistered('titan_operator_telegram') || MarketplaceHelper::isRegistered('titan_operator_whatsapp');
    }

    public static function channels()
    {
        //		return [
        //
        //		]
    }
}
