<?php

namespace Modules\CallingAgent\Services;

use Modules\CallingAgent\Data\TitanEchoVoiceData;
use Modules\CallingAgent\Services\Contracts\TitanEchoVoiceServiceContract;
use Modules\CallingAgent\Interfaces\CallingAgentRepositoryInterface;

/**
 * Service providing high-level operations for the TitanEchoVoice domain.
 */
class TitanEchoVoiceService implements TitanEchoVoiceServiceContract
{
    /**
     * Create a new service instance.
     *
     * @param CallingAgentRepositoryInterface $repository
     */
    public function __construct(protected CallingAgentRepositoryInterface $repository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id): TitanEchoVoiceData
    {
        $model = $this->repository->find($id);
        if (!$model) {
            // You could throw a domain-specific exception here. For now,
            // return an empty data object.
            return new TitanEchoVoiceData(null, '', []);
        }
        return TitanEchoVoiceData::fromModel($model);
    }
}