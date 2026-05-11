<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Controllers;

use App\Domains\Entity\Enums\EntityEnum;
use App\Domains\Entity\Facades\Entity;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\VoiceChatHistoryStoreRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Resources\TitanOperatorConversationHistoryResource;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoicechabotConversation;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceTitanOperator;
use App\Http\Controllers\Controller;
use App\Models\Usage;
use App\Services\Ai\ElevenLabsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class TitanOperatorVoiceHistoryController extends Controller
{
    /**
     * load conversation history with pagination
     */
    public function loadConversationWithPaginate(): AnonymousResourceCollection
    {
        $voiceTitanOperators = ExtVoiceTitanOperator::where('user_id', auth()->id())
            ->pluck('uuid')->toArray();

        $inProgressConvs = ExtVoicechabotConversation::where('status', 'in-progress')
            ->whereIn('operator_uuid', $voiceTitanOperators)
            ->orWhere('status', 'processing')
            ->get();

        foreach ($inProgressConvs as $conv) {
            $this->storeTranscripts($conv);
        }

        return TitanOperatorConversationHistoryResource::collection(
            ExtVoicechabotConversation::where('status', 'done')
                ->whereIn('operator_uuid', $voiceTitanOperators)
                ->with('chat_histories')
                ->paginate()
        );
    }

    /**
     * store new conversation
     */
    public function storeConversation(string $uuid, VoiceChatHistoryStoreRequest $request): JsonResponse
    {
        $titan_operator = ExtVoiceTitanOperator::whereUuid($uuid)->first();

        if (empty($titan_operator)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid uuid',
            ], 404);
        }

        try {
            $conversation = $titan_operator->conversations()->where('conversation_id')->first();
            if (! $conversation) {
                $conversation = $titan_operator->conversations()->create([
                    'conversation_id' => $request->validated()['conversation_id'],
                ]);
            } else {
                $conversation->chat_histories()->delete();
            }

            $this->storeTranscripts($conversation);

            return response()->json([
                'status' => 'success',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'create conversation failed',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * fetch transcripts from elevenlabs and store it on server database
     *
     * @param  ExtVoicechabotConversation  $conversation  server database conversation
     */
    public static function storeTranscripts(ExtVoicechabotConversation $conversation): void
    {
        $service = new ElevenLabsService;
        $res = $service->getConversationDetail($conversation->conversation_id);

        $operatorUUID = $conversation->operator_uuid;
        $titan_operator = ExtVoiceTitanOperator::whereUuid($operatorUUID)->first();
        if (! empty($titan_operator)) {
            $cost = data_get($res->original, 'resData.metadata.cost');
            $cost = $cost ?? 0;
            $chars = Str::random($cost);
            $user = $titan_operator->user;
            $driver = Entity::driver(EntityEnum::ELEVENLABS_VOICE_TITAN_OPERATOR)->forUser($user);
            $driver->input($chars)->calculateCredit()->decreaseCredit();
            Usage::getSingle()->updateWordCounts($driver->calculate());
        }

        if ($res->getData()->status == 'error') {
            return;
        }

        try {
            $resData = $res->getData()->resData;

            $conversation->status = $resData->status;
            $conversation->save();

            if ($resData->status != 'in-progress' && $resData->status != 'processing') {
                $transcripts = $resData->transcript;

                foreach ($transcripts as $transcript) {
                    $conversation->chat_histories()->create([
                        'role'    => $transcript->role,
                        'message' => $transcript->message,
                    ]);
                }
            }
        } catch (Throwable $th) {
            Log::error('store chat history error: ', [$th->getMessage()]);
        }
    }
}
