<?php

namespace Modules\CallingAgent\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CallingAgent\Services\Contracts\TitanEchoVoiceServiceContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * API controller exposing TitanEchoVoice endpoints.
 *
 * This controller demonstrates how to integrate the service layer with an
 * HTTP API. Additional CRUD actions can be implemented following the
 * same pattern.
 */
class TitanEchoVoiceApiController
{
    public function __construct(protected TitanEchoVoiceServiceContract $service)
    {
    }

    /**
     * Display a single TitanEchoVoice record.
     *
     * @param int $id
     * @return JsonResource
     */
    public function show(int $id): JsonResource
    {
        $data = $this->service->getById($id);
        if ($data->id === null) {
            throw new NotFoundHttpException('TitanEchoVoice not found');
        }
        return new JsonResource($data);
    }
}