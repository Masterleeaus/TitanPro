<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Controllers;

use App\Extensions\TitanOperator\System\Voice\Enums\TrainTypeEnum;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\Train\DataRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\Train\FileRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\Train\TextRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\Train\TrainRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Requests\Train\TrainUrlRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Resources\TitanOperatorTrainResource;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceoperatorTrain;
use App\Extensions\TitanOperator\System\Voice\Parsers\LinkParser;
use App\Extensions\TitanOperator\System\Voice\Services\ChabotVoiceService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class TitanOperatorVoiceTrainController extends Controller
{
    public function __construct(public ChabotVoiceService $service) {}

    /**
     * return voice titan_operator trian data
     */
    public function trainData(DataRequest $request): AnonymousResourceCollection
    {
        return TitanOperatorTrainResource::collection(
            $this->service->query()
                ->findOrFail($request->validated('id'))
                ->trains()
                ->when($request->validated('type'), fn ($query) => $query->where('type', $request->validated('type')))
                ->get()
        );
    }

    /**
     * delete voice chabot trian data
     */
    public function delete(TrainRequest $request): JsonResponse
    {
        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $trains = $titan_operator->trains()->whereIn('id', $request->validated('data'))->get();
        foreach ($trains as $train) {
            if ($train->trained_at && $train->doc_id) {
                $train->trained_at = null;
                $train->save();

                $this->service->updateAgentWithKnowledgebase($train->operator_id);
                $this->service->deleteKnowledgebase($train->doc_id);
            }
            $train->delete();
        }

        return response()->json([
            'message' => 'Deleted successfully',
            'status'  => 200,
        ]);
    }

    /**
     * store file train data on database
     */
    public function trainFile(FileRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');

        $defaultDisk = 'public';
        $path = $file->store('titan_operator-voice', ['disk' => $defaultDisk]);

        ExtVoiceoperatorTrain::create([
            'type'       => TrainTypeEnum::file,
            'name'		     => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'user_id'	   => Auth::id(),
            'operator_id' => $titan_operator->getKey(),
            'file'       => $path,
        ]);

        return TitanOperatorTrainResource::collection(
            $titan_operator->trains()->whereNotNull('file')->get()
        );
    }

    /**
     * store text train data on database
     */
    public function trainText(TextRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        ExtVoiceoperatorTrain::create([
            'type'       => TrainTypeEnum::text,
            'user_id'    => Auth::id(),
            'operator_id' => $titan_operator->getKey(),
            'name'       => $request->validated('title'),
            'text'       => $request->validated('content'),
        ]);

        return TitanOperatorTrainResource::collection(
            $titan_operator->trains()
                ->wherenull('file')
                ->whereNull('url')->get()
        );
    }

    /**
     * store url train data on database
     */
    public function trainUrl(TrainUrlRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        app(LinkParser::class)
            ->setBaseUrl($request->validated('url'))
            ->crawl((bool) $request->validated('single'))
            ->insertEmbeddings($titan_operator);

        return TitanOperatorTrainResource::collection(
            $titan_operator->trains()->whereNotNull('url')->get()
        );
    }

    /**
     * train on elevenlabs
     */
    public function generateEmbedding(TrainRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        ini_set('max_execution_time', -1);

        $data = $request->validated('data');
        $trains = ExtVoiceoperatorTrain::query()
            ->whereNull('trained_at')
            ->whereIn('id', $data)
            ->get();

        foreach ($trains as $train) {
            $content = '';
            if ($train->type == 'text') {
                $content = $train->text;
            } elseif ($train->type == 'url') {
                $content = $train->url;
            } elseif ($train->type == 'file') {
                $defaultDisk = 'public';
                $path = $train->file;
                $storagePath = config('filesystems.disks.' . $defaultDisk . '.root') . '/' . $path;

                $content = new UploadedFile(
                    $storagePath,
                    basename($path),
                    mime_content_type($storagePath),
                    null,
                    true
                );
            }

            $res = $this->service->addKnowledgebase($train->type, $content, $train->name);

            if ($res->getData()->status == 'success') {
                $train->update([
                    'name' 			      => $res->getData()->resData?->name,
                    'doc_id' 		     => $res->getData()->resData?->id,
                    'trained_at'    => now(),
                ]);
            }
        }

        $this->service->updateAgentWithKnowledgebase($trains[0]->operator_id);

        return TitanOperatorTrainResource::collection($titan_operator->trains()->whereIn('id', $data)->get());
    }
}
