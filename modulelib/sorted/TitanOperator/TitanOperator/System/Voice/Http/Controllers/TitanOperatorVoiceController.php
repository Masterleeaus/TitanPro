<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Controllers;

use App\Domains\Entity\Enums\EntityEnum;
use App\Domains\Entity\Facades\Entity as EntityFacade;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\VoiceTitanOperatorStoreRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\VoiceTitanOperatorUpdateRequest;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceTitanOperator;
use App\Extensions\TitanOperator\System\Voice\Services\ChabotVoiceService;
use App\Helpers\Classes\Helper;
use App\Helpers\Classes\RateLimiter\RateLimiter;
use App\Http\Controllers\Controller;
use App\Services\Ai\ElevenLabsService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TitanOperatorVoiceController extends Controller
{
    public function __construct(public ChabotVoiceService $service) {}

    // index
    public function index(): View
    {
        $operators = $this->service->query()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')->paginate(perPage: 100);

        return view('titan_operator-voice::index', [
            'operators' => $operators,
            'avatars' 	=> $this->service->avatars(),
            'voices' 	 => $this->service->getVoices(),
        ]);
    }

    // store voice titan_operator
    public function store(VoiceTitanOperatorStoreRequest $request): JsonResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $reqData = $request->validated();
        if (isset($reqData['language']) && $reqData['language'] == 'auto') {
            unset($reqData['language']);
        }

        $res = $this->service->createAgent($reqData);

        if ($res->getData()->status === 'success') {
            $reqData['agent_id'] = $res->getData()->resData->agent_id;
            $reqData['voice_id'] = ElevenLabsService::DEFAULT_ELEVENLABS_VOICE_ID;
            $reqData['ai_model'] = ElevenLabsService::DEFAULT_ELEVENLABS_MODEL;
            if (isset($reqData['language']) && $reqData['language'] == 'en') {
                $reqData['ai_model'] = ElevenLabsService::DEFAULT_ELEVENLABS_MODEL_FOR_ENGLISH;
            }
            $titan_operator = ExtVoiceTitanOperator::create($reqData);

            return JsonResource::make($titan_operator);
        }

        return $res->setStatusCode(422);

    }

    // update voice titan_operator
    public function update(VoiceTitanOperatorUpdateRequest $request): JsonResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $reqData = $request->validated();

        if (isset($reqData['language']) && $reqData['language'] == 'auto') {
            unset($reqData['language']);
        }

        $titan_operator = ExtVoiceTitanOperator::findOrFail($reqData['id']);
        $titan_operator?->update($reqData);

        $this->service->updateAgent($titan_operator->id);

        return JsonResource::make($titan_operator);
    }

    // delete voice titan_operator
    public function delete(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate(['id' => 'required']);

        $titan_operator = $this->service->query()->findOrFail($request->get('id'));

        if ($titan_operator->getAttribute('user_id') === Auth::id()) {
            $this->service->deleteAgent($titan_operator->agent_id);
            $titan_operator->delete();
        } else {
            abort(403);
        }

        return response()->json([
            'message' => 'Voice TitanOperator deleted successfully',
            'type'    => 'success',
            'status'  => 200,
        ]);
    }

    /**
     * voice titan_operator frame view
     */
    public function frame(string $uuid): View|Response
    {
        $titan_operator = ExtVoiceTitanOperator::whereUuid($uuid)->firstOrFail();
        if ($titan_operator) {
            return view('titan_operator-voice::frame', compact('titan_operator'));
        } else {
            return response('Incorrect UUID', 404);
        }
    }

    public function checkVoiceBalance(Request $request): ?JsonResponse
    {
        if (Helper::appIsDemo()) {

            $clientIp = Helper::getRequestIp();
            $rateLimiter = new RateLimiter('voice-chat-attempt', 25);

            if ($rateLimiter->attempt($clientIp)) {
                return response()->json(['status' => 'success', 'message' => 'Demo mode'], 200);

            }

            return response()->json(['status' => 'error', 'message' => 'Exceeded messages limit on demo'], 200);
        }

        $uuId = $request->input('uuId');
        $titan_operator = ExtVoiceTitanOperator::whereUuid($uuId)->first();
        if (! empty($titan_operator)) {
            $user = $titan_operator->user;
            $driver = EntityFacade::driver(EntityEnum::ELEVENLABS_VOICE_TITAN_OPERATOR)->forUser($user);

            try {
                $driver->redirectIfNoCreditBalance();
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'status'  => 'error',
                ], 200);
            }
        }

        return response()->json(['status' => 'success', 'message' => ''], 200);
    }
}
