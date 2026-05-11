<?php

namespace App\Extensions\TitanOperator\System\Channels\Messenger\Services;

use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;

class MessengerService
{
    public TitanOperatorChannel $operatorChannel;

    public function sendText($message, $receiver)
    {
        $access_token = data_get($this->operatorChannel['credentials'], 'access_token', '');

        $simpleMessengerBot = new \App\Extensions\TitanOperator\System\Channels\Messenger\Helpers\SimpleMessengerBot($access_token);

        $simpleMessengerBot->sendMessage($receiver, $message);
    }

    public function getTitanOperatorChannel(): TitanOperatorChannel
    {
        return $this->operatorChannel;
    }

    public function setTitanOperatorChannel(TitanOperatorChannel $operatorChannel): self
    {
        $this->operatorChannel = $operatorChannel;

        return $this;
    }
}
