<?php

namespace App\Extensions\TitanOperator\System\Channels\Whatsapp\Services\Twillio;

use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use Exception;
use Twilio\Rest\Client;

class TwilioWhatsappService
{
    public TitanOperatorChannel $operatorChannel;

    public ?string $twilioPhone = null;

    public function sendText($message, $receiver)
    {
        $client = $this->client();

        $from = $this->operatorChannel->isSandbox()
            ? data_get($this->operatorChannel['credentials'], 'whatsapp_sandbox_phone')
            : data_get($this->operatorChannel['credentials'], 'whatsapp_phone');

        try {

            $receiver = $this->receiverCheck($receiver);

            $message = $client->messages->create(
                $receiver,
                [
                    'from' => 'whatsapp:' . $from,
                    'body' => $message,
                ]
            );

            return [
                'properties' => $this->properties($message),
                'message'    => trans('Message sent'),
                'status'     => true,
            ];
        } catch (Exception $exception) {
            return [
                'message' => $exception->getMessage(),
                'status'  => false,
            ];
        }
    }

    public function receiverCheck(string $receiver): string
    {
        if (strpos($receiver, 'whatsapp:') !== 0) {
            $receiver = 'whatsapp:' . $receiver;
        }

        return $receiver;
    }

    public function properties($message): array
    {
        return [
            'body'                => $message->__get('body'),
            'numSegments'         => $message->__get('numSegments'),
            'direction'           => $message->__get('direction'),
            'from'                => $message->__get('from'),
            'to'                  => $message->__get('to'),
            'dateUpdated'         => $message->__get('dateUpdated'),
            'price'               => $message->__get('price'),
            'errorMessage'        => $message->__get('errorMessage'),
            'uri'                 => $message->__get('uri'),
            'accountSid'          => $message->__get('accountSid'),
            'numMedia'            => $message->__get('numMedia'),
            'status'              => $message->__get('status'),
            'messagingServiceSid' => $message->__get('messagingServiceSid'),
            'sid'                 => $message->__get('sid'),
            'dateSent'            => $message->__get('dateSent'),
            'dateCreated'         => $message->__get('dateCreated'),
            'errorCode'           => $message->__get('errorCode'),
            'priceUnit'           => $message->__get('priceUnit'),
            'apiVersion'          => $message->__get('apiVersion'),
            'subresourceUris'     => $message->__get('subresourceUris'),
        ];
    }

    public function client(): Client
    {
        $username = data_get($this->operatorChannel['credentials'], 'whatsapp_sid');

        $password = data_get($this->operatorChannel['credentials'], 'whatsapp_token');

        $this->twilioPhone = data_get($this->operatorChannel['credentials'], 'whatsapp_phone');

        return new Client($username, $password);
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
