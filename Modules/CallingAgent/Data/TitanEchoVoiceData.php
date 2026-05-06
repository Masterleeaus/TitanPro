<?php

namespace Modules\CallingAgent\Data;

use Modules\CallingAgent\Models\CallingAgent;

/**
 * Data transfer object for the TitanEchoVoice entity.
 *
 * Although the underlying persistence model is CallingAgent, this class
 * provides a stable shape for exchanging TitanEchoVoice data within the
 * application layer. You can extend this class with additional
 * properties or methods as the domain evolves.
 */
class TitanEchoVoiceData
{
    /**
     * Create a new data object from a CallingAgent model instance.
     *
     * @param CallingAgent $model
     * @return static
     */
    public static function fromModel(CallingAgent $model): static
    {
        $data = new static();
        $data->id = $model->id;
        $data->name = $model->name ?? '';
        $data->settings = $model->settings ?? [];
        return $data;
    }

    /**
     * Populate this data object with values.
     *
     * @param int|null $id
     * @param string $name
     * @param array $settings
     */
    public function __construct(public ?int $id = null, public string $name = '', public array $settings = [])
    {
    }
}