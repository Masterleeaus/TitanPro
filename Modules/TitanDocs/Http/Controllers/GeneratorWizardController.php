<?php

namespace Modules\TitanDocs\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanDocs\Models\WizardSession;
use Modules\TitanDocs\Services\TitanZeroStandardsService;

class GeneratorWizardController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Titan Docs Generator');
    }

    public function start()
    {
        $user = auth()->user();

        $session = WizardSession::create([
            'company_id' => $user?->organization_id,
            'user_id' => $user?->id,
            'doc_kind' => 'doc',
            'current_step' => 1,
            'status' => 'draft',
            'payload_json' => [],
        ]);

        return redirect()->route('titan.docs.generator.step', ['session' => $session->id, 'step' => 1]);
    }

    public function step(Request $request, int $session, int $step)
    {
        $wiz = WizardSession::query()->where('id', $session)->firstOrFail();

        $step = max(1, min(4, $step));
        $wiz->current_step = $step;
        $wiz->save();

        $data = $wiz->payload_json ?? [];

        return view('titandocs::generator.step'.$step, $this->data + [
            'wizard' => $wiz,
            'step' => $step,
            'data' => $data,
        ]);
    }

    public function save(Request $request, int $session, int $step)
    {
        $wiz = WizardSession::query()->where('id', $session)->firstOrFail();

        $payload = $wiz->payload_json ?? [];
        $incoming = $request->except(['_token']);

        if (isset($incoming['doc_kind'])) {
            $incoming['doc_kind'] = in_array($incoming['doc_kind'], ['doc','swms'], true) ? $incoming['doc_kind'] : 'doc';
            $wiz->doc_kind = $incoming['doc_kind'];
        }

        $payload['step_'.$step] = $incoming;

        if ((int)$step === 3) {
            // Always ask; only fetch when user selects "Yes"
            $want = (string)($incoming['want_official_standards'] ?? '0');
            $suggestions = [];

            if ($want === '1') {
                $svc = new TitanZeroStandardsService();
                $suggestions = $svc->suggest([
                    'want_official_standards' => true,
                    'jurisdiction' => $incoming['jurisdiction'] ?? 'AU',
                    'domains' => $incoming['domains'] ?? [],
                    'doc_kind' => $wiz->doc_kind,
                    'doc_type' => $payload['step_1']['doc_type'] ?? null,
                    'trade' => $payload['step_2']['trade'] ?? null,
                ]);
            }

            $payload['step_3']['standards_suggestions'] = $suggestions;
        }

        $wiz->payload_json = $payload;
        $wiz->current_step = (int)$step;
        $wiz->save();

        $next = (int)$step + 1;
        if ($next <= 4) {
            return redirect()->route('titan.docs.generator.step', ['session' => $wiz->id, 'step' => $next]);
        }

        return redirect()->route('titan.docs.generator.review', ['session' => $wiz->id]);
    }

    public function review(int $session)
    {
        $wiz = WizardSession::query()->where('id', $session)->firstOrFail();
        $data = $wiz->payload_json ?? [];

        return view('titandocs::generator.review', $this->data + [
            'wizard' => $wiz,
            'data' => $data,
        ]);
    }

    public function complete(Request $request, int $session)
    {
        $wiz = WizardSession::query()->where('id', $session)->firstOrFail();
        $wiz->status = 'complete';
        $wiz->save();

        // Hand off to existing generator UI; it can read ?wizard=<id> later if desired
        return redirect()->route('titan.docs.index', ['wizard' => $wiz->id])->with('success', __('Wizard complete. Choose a template to generate your document.'));
    }
}
