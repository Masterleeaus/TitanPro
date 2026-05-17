<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Controllers;

use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceTitanOperator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TitanOperatorVoiceEmbbedController extends Controller
{
    /**
     * detail of external voice titan_operator information by uuid
     */
    public function index(string|int $uuid): JsonResource
    {
        $ExtVoiceTitanOperator = ExtVoiceTitanOperator::where('uuid', $uuid)->first();

        return JsonResource::make($ExtVoiceTitanOperator);
    }
}
