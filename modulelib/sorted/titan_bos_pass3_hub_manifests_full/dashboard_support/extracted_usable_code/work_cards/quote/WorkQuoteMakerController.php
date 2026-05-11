<?php

namespace App\Extensions\ProductPhotography\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Schema;
use App\Extensions\ProductPhotography\Requests\GenerateQuoteVisualRequest;
use App\Extensions\ProductPhotography\Services\PromptBuilder;
use App\Extensions\ProductPhotography\Services\RenderPayloadFactory;
use App\Extensions\ProductPhotography\Services\QuoteMetadataFactory;
use App\Extensions\ProductPhotography\Services\QuoteVisualRepository;
use App\Extensions\ProductPhotography\Services\ImageRenderWorker;
use App\Extensions\ProductPhotography\Models\QuoteTemplate;
use App\Extensions\ProductPhotography\Models\QuoteBot;
use App\Extensions\ProductPhotography\Models\RevenueBuilder;
use App\Extensions\ProductPhotography\Services\RevenueExecutionBridge;

class QuoteMakerController
{
    public function __construct(
        protected PromptBuilder $promptBuilder,
        protected RenderPayloadFactory $renderPayloadFactory,
        protected QuoteMetadataFactory $metadataFactory,
        protected QuoteVisualRepository $repository,
        protected ImageRenderWorker $renderWorker,
        protected RevenueExecutionBridge $executionBridge
    ) {
    }

    protected function bootViewNamespace(): void
    {
        $viewPath = app_path('Extensions/ProductPhotography/resources/views');

        if (is_dir($viewPath)) {
            ViewFacade::addNamespace('productphotography', $viewPath);
        }
    }

    protected function fallbackTemplates(): array
    {
        return [
            ['name' => 'House Cleaning Refresh', 'vertical' => 'Cleaning', 'service_type' => 'House Cleaning', 'variation' => 'Recurring', 'theme' => 'Clean Professional', 'visual_mode' => 'before_after', 'package_tier' => 'standard', 'summary' => 'Recurring clean with visible tidy transformation.'],
            ['name' => 'Deep Clean Premium', 'vertical' => 'Cleaning', 'service_type' => 'Deep Cleaning', 'variation' => 'Premium', 'theme' => 'Premium Home Service', 'visual_mode' => 'quote_preview', 'package_tier' => 'premium', 'summary' => 'Higher-detail clean with premium presentation.'],
            ['name' => 'Pressure Wash Driveway', 'vertical' => 'Pressure Washing', 'service_type' => 'Pressure Washing', 'variation' => 'Residential', 'theme' => 'Before/After Heavy', 'visual_mode' => 'before_after', 'package_tier' => 'premium', 'summary' => 'Highlights cleaned concrete edges and stain reduction.'],
            ['name' => 'Garden Tidy Proposal', 'vertical' => 'Gardening', 'service_type' => 'Garden Tidy', 'variation' => 'Standard', 'theme' => 'Fast Local Offer', 'visual_mode' => 'quote_preview', 'package_tier' => 'standard', 'summary' => 'Useful for mowing, edging, pruning, and green waste removal.'],
            ['name' => 'Handyman Repair Scope', 'vertical' => 'Handyman', 'service_type' => 'Handyman Repairs', 'variation' => 'Maintenance', 'theme' => 'Commercial Scope', 'visual_mode' => 'scope_visual', 'package_tier' => 'standard', 'summary' => 'Shows work zones and expected finished repair result.'],
            ['name' => 'End of Lease Reset', 'vertical' => 'Cleaning', 'service_type' => 'End of Lease', 'variation' => 'Vacate', 'theme' => 'Insurance / Compliance Style', 'visual_mode' => 'proposal_cover', 'package_tier' => 'premium', 'summary' => 'Compliance-first vacate reset with stronger scope framing.'],
        ];
    }

    protected function getTemplatesData()
    {
        if (Schema::hasTable('ext_quotemaker_templates')) {
            return QuoteTemplate::query()->latest()->get();
        }

        return collect($this->fallbackTemplates());
    }

    public function index(): View
    {
        $this->bootViewNamespace();

        return view('productphotography::index', [
            'visualModes' => config('productphotography.visual_modes', []),
            'packageTiers' => config('productphotography.package_tiers', []),
            'recent' => $this->repository->latest(12),
            'surfaceName' => config('productphotography.surface_name', 'QuoteMaker'),
            'templates' => $this->getTemplatesData()->take(6),
        ]);
    }

    public function generate(GenerateQuoteVisualRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $prompt = $this->promptBuilder->build($payload);
        $meta = $this->metadataFactory->make($payload);
        $renderPayload = $this->renderPayloadFactory->make($payload, $prompt);

        $record = $this->repository->create(array_merge($payload, $meta, [
            'generated_prompt' => $prompt,
            'render_payload_json' => json_encode($renderPayload, JSON_UNESCAPED_SLASHES),
            'status' => 'queued',
            'image_path' => null,
        ]));

        $renderStatus = $this->renderWorker->render($renderPayload);

        return response()->json([
            'status' => 'ok',
            'message' => 'Quote visual payload prepared successfully.',
            'record_id' => $record->id,
            'prompt' => $prompt,
            'meta' => $meta,
            'render_status' => $renderStatus,
        ]);
    }

    public function gallery(): View
    {
        $this->bootViewNamespace();

        return view('productphotography::gallery', [
            'items' => $this->repository->latest(50),
            'surfaceName' => config('productphotography.surface_name', 'QuoteMaker'),
        ]);
    }

    public function templates(): View
    {
        $this->bootViewNamespace();

        return view('productphotography::templates', [
            'surfaceName' => config('productphotography.surface_name', 'QuoteMaker'),
            'templates' => $this->getTemplatesData(),
        ]);
    }

    public function builder(): View
    {
        $this->bootViewNamespace();

        return view('productphotography::create.index', [
            'surfaceName' => config('productphotography.surface_name', 'QuoteMaker'),
        ]);
    }

    public function storeBuilder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'string', 'in:quote,booking,invoice'],
            'vertical' => ['required', 'string', 'max:100'],
            'service_type' => ['required', 'string', 'max:150'],
            'variation' => ['nullable', 'string', 'max:100'],
            'theme' => ['required', 'string', 'max:120'],
            'visual_mode' => ['required', 'string', 'max:60'],
            'package_tier' => ['required', 'string', 'max:60'],
            'site_type' => ['nullable', 'string', 'max:150'],
            'scope_notes' => ['nullable', 'string'],
            'pricing_model' => ['nullable', 'string', 'max:120'],
            'estimated_hours' => ['nullable', 'numeric'],
            'estimated_price_min' => ['nullable', 'numeric'],
            'estimated_price_max' => ['nullable', 'numeric'],
            'quote_ai_enabled' => ['nullable', 'boolean'],
            'ai_questions' => ['nullable', 'array'],
            'follow_up_delay_hours' => ['nullable', 'integer', 'min:1', 'max:720'],
            'follow_up_channel' => ['nullable', 'string', 'max:120'],
            'attach_quote_bot' => ['nullable', 'boolean'],
            'quote_bot_name' => ['nullable', 'string', 'max:255'],
            'auto_create_booking' => ['nullable', 'boolean'],
            'auto_create_job' => ['nullable', 'boolean'],
            'auto_send_invoice' => ['nullable', 'boolean'],
            'auto_negotiate' => ['nullable', 'boolean'],
            'negotiation_floor_percent' => ['nullable', 'numeric'],
            'negotiation_notes' => ['nullable', 'string'],
            'preferred_time_window' => ['nullable', 'string', 'max:255'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'due_days' => ['nullable', 'integer', 'min:0', 'max:365'],
        ]);

        $metadata = [
            'site_type' => $validated['site_type'] ?? null,
            'scope_notes' => $validated['scope_notes'] ?? null,
            'estimated_hours' => $validated['estimated_hours'] ?? null,
            'estimated_price_min' => $validated['estimated_price_min'] ?? null,
            'estimated_price_max' => $validated['estimated_price_max'] ?? null,
            'quote_ai_enabled' => (bool) ($validated['quote_ai_enabled'] ?? false),
            'ai_questions' => $validated['ai_questions'] ?? [],
            'preferred_time_window' => $validated['preferred_time_window'] ?? null,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'due_days' => $validated['due_days'] ?? null,
            'quote_bridge' => $this->buildQuoteBridge($validated),
            'pricing_guidance' => $this->buildPricingGuidance($validated),
        ];

        $summary = $this->buildBuilderSummary($validated);

        $builderData = array_merge([
            'summary' => $summary,
            'follow_up_delay_hours' => $validated['follow_up_delay_hours'] ?? 24,
            'follow_up_channel' => $validated['follow_up_channel'] ?? 'email_sms',
            'auto_create_booking' => (bool) ($validated['auto_create_booking'] ?? false),
            'auto_create_job' => (bool) ($validated['auto_create_job'] ?? false),
            'auto_send_invoice' => (bool) ($validated['auto_send_invoice'] ?? false),
            'auto_negotiate' => (bool) ($validated['auto_negotiate'] ?? false),
            'metadata_json' => json_encode($metadata, JSON_UNESCAPED_SLASHES),
            'status' => 'active',
        ], collect($validated)->only([
            'name', 'mode', 'vertical', 'service_type', 'variation', 'theme', 'visual_mode', 'package_tier', 'pricing_model'
        ])->toArray());

        $builder = null;
        if (Schema::hasTable('ext_quotemaker_builders')) {
            $builder = RevenueBuilder::create($builderData);
        }

        $template = null;
        $templateData = [
            'name' => $validated['name'],
            'vertical' => $validated['vertical'],
            'service_type' => $validated['service_type'],
            'variation' => $validated['variation'] ?? null,
            'theme' => $validated['theme'],
            'visual_mode' => $validated['visual_mode'],
            'package_tier' => $validated['package_tier'],
            'summary' => $summary,
            'pricing_model' => $validated['pricing_model'] ?? null,
            'metadata_json' => json_encode($metadata, JSON_UNESCAPED_SLASHES),
            'is_active' => true,
        ];

        if (Schema::hasTable('ext_quotemaker_templates')) {
            $template = QuoteTemplate::create($templateData);
        }

        $botAttached = false;
        if (($validated['attach_quote_bot'] ?? false) && Schema::hasTable('ext_quotemaker_quote_bots')) {
            QuoteBot::create([
                'template_id' => $template?->id,
                'name' => $validated['quote_bot_name'] ?? ($validated['name'] . ' Bot'),
                'follow_up_delay_hours' => $validated['follow_up_delay_hours'] ?? 24,
                'follow_up_channel' => $validated['follow_up_channel'] ?? 'email_sms',
                'auto_create_booking' => (bool) ($validated['auto_create_booking'] ?? false),
                'auto_create_job' => (bool) ($validated['auto_create_job'] ?? false),
                'auto_negotiate' => (bool) ($validated['auto_negotiate'] ?? false),
                'negotiation_rules_json' => json_encode([
                    'floor_percent' => $validated['negotiation_floor_percent'] ?? null,
                    'notes' => $validated['negotiation_notes'] ?? null,
                    'mode' => $validated['mode'],
                ], JSON_UNESCAPED_SLASHES),
                'status' => 'active',
            ]);
            $botAttached = true;
        }

        $executionPreview = $this->executionBridge->preview(array_merge($builderData, ['id' => $builder?->id]));
        $executionResult = $this->executionBridge->execute(array_merge($builderData, ['id' => $builder?->id]), [
            'team_id' => auth()->user()?->team_id,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Revenue builder saved successfully.',
            'builder_name' => $validated['name'],
            'mode' => $validated['mode'],
            'bot_attached' => $botAttached,
            'builder_saved' => (bool) $builder,
            'template_saved' => (bool) $template,
            'execution_preview' => $executionPreview,
            'execution_result' => $executionResult,
            'redirect_url' => route('dashboard.user.quotemaker.templates'),
        ]);
    }

    protected function buildBuilderSummary(array $data): string
    {
        $mode = ucfirst($data['mode'] ?? 'Quote');
        $vertical = $data['vertical'] ?? 'Service';
        $variation = $data['variation'] ?? 'Standard';
        $theme = $data['theme'] ?? 'Clean Professional';
        $package = $data['package_tier'] ?? 'standard';

        return "{$mode} / {$vertical} / {$variation} using the {$theme} theme and {$package} package framing.";
    }

    protected function buildPricingGuidance(array $data): array
    {
        $min = $data['estimated_price_min'] ?? null;
        $max = $data['estimated_price_max'] ?? null;
        $hours = $data['estimated_hours'] ?? null;

        $flags = [];

        if ($min !== null && $max !== null && $max < $min) {
            $flags[] = 'price_range_invalid';
        }

        if ($hours !== null && $hours > 10) {
            $flags[] = 'long_job_check_access_and_materials';
        }

        return [
            'min' => $min,
            'max' => $max,
            'hours' => $hours,
            'flags' => $flags,
        ];
    }

    protected function buildQuoteBridge(array $data): array
    {
        return [
            'mode' => $data['mode'] ?? 'quote',
            'acceptance_actions' => [
                'quote' => [
                    'can_create_booking' => (bool) ($data['auto_create_booking'] ?? false),
                    'can_create_job' => (bool) ($data['auto_create_job'] ?? false),
                ],
                'booking' => [
                    'preferred_time_window' => $data['preferred_time_window'] ?? null,
                    'can_create_job' => (bool) ($data['auto_create_job'] ?? false),
                ],
                'invoice' => [
                    'payment_terms' => $data['payment_terms'] ?? null,
                    'due_days' => $data['due_days'] ?? null,
                    'can_auto_send_invoice' => (bool) ($data['auto_send_invoice'] ?? false),
                ],
            ],
        ];
    }
}
