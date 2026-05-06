<?php

namespace Modules\CallingAgent\Services\Contracts;

use Modules\CallingAgent\Data\TitanEchoVoiceData;

/**
 * Contract for the TitanEchoVoice service layer.
 *
 * Defines the operations available for interacting with the TitanEchoVoice
 * domain. Services implementing this interface should encapsulate
 * application logic and coordinate between repositories and other
 * infrastructure.
 */
interface TitanEchoVoiceServiceContract
{
    /**
     * Retrieve a TitanEchoVoice entity by its identifier.
     *
     * @param int $id
     * @return TitanEchoVoiceData
     */
    public function getById(int $id): TitanEchoVoiceData;
}