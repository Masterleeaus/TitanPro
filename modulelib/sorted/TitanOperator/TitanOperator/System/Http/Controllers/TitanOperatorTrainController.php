<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers;

use App\Domains\Engine\Enums\EngineEnum;
use App\Domains\Entity\Enums\EntityEnum;
use App\Extensions\TitanOperator\System\Enums\EmbeddingTypeEnum;
use App\Extensions\TitanOperator\System\Http\Requests\Train\DataRequest;
use App\Extensions\TitanOperator\System\Http\Requests\Train\EmbedingRequest;
use App\Extensions\TitanOperator\System\Http\Requests\Train\FileRequest;
use App\Extensions\TitanOperator\System\Http\Requests\Train\QaRequest;
use App\Extensions\TitanOperator\System\Http\Requests\Train\TextRequest;
use App\Extensions\TitanOperator\System\Http\Requests\Train\TrainUrlRequest;
use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorEmbeddingResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorEmbedding;
use App\Extensions\TitanOperator\System\Parsers\ExcelParser;
use App\Extensions\TitanOperator\System\Parsers\LinkParser;
use App\Extensions\TitanOperator\System\Parsers\PdfParser;
use App\Extensions\TitanOperator\System\Parsers\TextParser;
use App\Extensions\TitanOperator\System\Services\TitanOperatorService;
use App\Extensions\TitanOperator\System\Services\OpenAI\EmbedingService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TitanOperatorTrainController extends Controller
{
    public function __construct(public TitanOperatorService $service) {}

    public function train(TitanOperator $titan_operator): View
    {
        $this->authorize('view', $titan_operator);

        return view('titan_operator::train', [
            'titan_operator' => $titan_operator,
        ]);
    }

    public function trainData(DataRequest $request): AnonymousResourceCollection
    {
        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        return TitanOperatorEmbeddingResource::collection(
            $titan_operator
                ->embeddings()
                ->when($request->validated('type'), fn ($query) => $query->where('type', $request->validated('type')))
                ->get()
        );
    }

    public function deleteEmbedding(EmbedingRequest $request): JsonResponse
    {
        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        $titan_operator->embeddings()->whereIn('id', $request->validated('data'))->delete();

        return response()->json([
            'message' => 'Embedding deleted successfully',
            'status'  => 200,
        ]);
    }

    public function generateEmbedding(EmbedingRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        ini_set('max_execution_time', -1);

        $data = $request->validated('data');

        $embeddings = TitanOperatorEmbedding::query()
            ->whereNull('embedding')
            ->whereIn('id', $data)
            ->get();

        $aiEmbeddingModel = EntityEnum::TEXT_EMBEDDING_3_SMALL;

        if (! EntityEnum::from($titan_operator->getAttribute('ai_embedding_model'))) {
            $titan_operator->update([
                'ai_embedding_model' => EntityEnum::TEXT_EMBEDDING_3_SMALL->value,
            ]);

            $aiEmbeddingModel = EntityEnum::TEXT_EMBEDDING_3_SMALL;
        }

        foreach ($embeddings as $embedding) {
            $embeddingJson = app(EmbedingService::class)
                ->setTitanOperator($titan_operator)
                ->setEntity($aiEmbeddingModel)
                ->generateEmbedding($embedding->getAttribute('content'));

            $embedding->update([
                'embedding'    => $embeddingJson->toArray(),
                'trained_at'   => now(),
            ]);
        }

        return TitanOperatorEmbeddingResource::collection($titan_operator->embeddings()->get());
    }

    public function trainText(TextRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        TitanOperatorEmbedding::query()
            ->create([
                'type'       => EmbeddingTypeEnum::text,
                'operator_id' => $titan_operator->getKey(),
                'url'        => null,
                'file'       => null,
                'engine'     => EngineEnum::OPEN_AI->value,
                'title'      => $request->validated('title'),
                'content'    => $request->validated('content'),
            ]);

        return TitanOperatorEmbeddingResource::collection(
            $titan_operator->embeddings()
                ->wherenull('file')
                ->whereNull('url')->get()
        );
    }

    public function trainQa(QaRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        TitanOperatorEmbedding::query()
            ->create([
                'type'       => EmbeddingTypeEnum::qa,
                'operator_id' => $titan_operator->getKey(),
                'url'        => null,
                'file'       => null,
                'engine'     => EngineEnum::OPEN_AI->value,
                'title'      => $request->validated('question'),
                'content'    => $request->validated('question') . ' : ' . $request->validated('answer'),
            ]);

        return TitanOperatorEmbeddingResource::collection(
            $titan_operator->embeddings()
                ->wherenull('file')
                ->whereNull('url')->get()
        );
    }

    public function trainUrl(TrainUrlRequest $request): JsonResponse|AnonymousResourceCollection
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        $titan_operator->setAttribute('engine', EngineEnum::OPEN_AI->value);

        app(LinkParser::class)
            ->setBaseUrl($request->validated('url'))
            ->crawl((bool) $request->validated('single'))
            ->insertEmbeddings($titan_operator);

        return TitanOperatorEmbeddingResource::collection(
            $titan_operator->embeddings()->whereNotNull('url')->get()
        );
    }

    public function trainFile(FileRequest $request)
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->query()->findOrFail($request->validated('id'));

        $this->authorize('train', $titan_operator);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');

        $extension = $file->guessExtension();

        $defaultDisk = 'public';

        $path = $file->store('titan_operator', ['disk' => $defaultDisk]);

        $name = $file->getClientOriginalName();

        $storagePath = config('filesystems.disks.' . $defaultDisk . '.root') . '/' . $path;

        $parser = match (true) {
            in_array($extension, ['xlsx', 'xls', 'csv']) => app(ExcelParser::class),
            in_array($extension, ['txt', 'json'])        => app(TextParser::class),
            default                                      => app(PdfParser::class),
        };

        $text = $parser->setPath($storagePath)->parse();

        TitanOperatorEmbedding::query()
            ->firstOrCreate([
                'type'       => EmbeddingTypeEnum::file,
                'operator_id' => $titan_operator->getKey(),
                'url'        => null,
                'file'       => $path,
                'engine'     => EngineEnum::OPEN_AI->value,
            ], [
                'title'    => $name,
                'content'  => $text,
            ]);

        return TitanOperatorEmbeddingResource::collection(
            $titan_operator->embeddings()->whereNotNull('file')->get()
        );
    }
}
