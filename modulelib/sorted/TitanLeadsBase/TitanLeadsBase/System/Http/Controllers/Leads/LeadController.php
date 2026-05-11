<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Extensions\TitanLeads\System\Models\Leads\PcLead;
use App\Extensions\TitanLeads\System\Models\Leads\PcPipeline;
use App\Extensions\TitanLeads\System\Models\Leads\PcStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $userId = (int) Auth::id();

        $leads = PcLead::query()
            ->where('user_id', $userId)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('company_name', 'like', "%{$q}%")
                        ->orWhere('person_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('titan-leads::leads.index', compact('leads', 'q'));
    }

    public function create()
    {
        $userId = (int) Auth::id();

        // Create a default pipeline + stages if none exist
        $pipeline = PcPipeline::query()->firstOrCreate(
            ['user_id' => $userId, 'name' => 'Property Managers'],
            ['sort_order' => 0, 'is_active' => true]
        );

        $defaults = ['New', 'Contacted', 'Qualified', 'Proposal Sent', 'Won', 'Lost'];
        foreach ($defaults as $i => $name) {
            PcStage::query()->firstOrCreate(
                ['user_id' => $userId, 'pipeline_id' => $pipeline->id, 'name' => $name],
                ['sort_order' => $i, 'is_won' => $name === 'Won', 'is_lost' => $name === 'Lost']
            );
        }

        $pipelines = PcPipeline::query()->where('user_id', $userId)->orderBy('sort_order')->get();
        $stages    = PcStage::query()->where('user_id', $userId)->where('pipeline_id', $pipeline->id)->orderBy('sort_order')->get();

        return view('titan-leads::leads.create', compact('pipelines', 'stages'));
    }

    public function store(Request $request)
    {
        $userId = (int) Auth::id();

        $data = $request->validate([
            'lead_type'     => 'nullable|string|max:50',
            'company_name'  => 'nullable|string|max:255',
            'person_name'   => 'nullable|string|max:255',
            'email'         => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'pipeline_id'   => 'required|integer',
            'stage_id'      => 'required|integer',
            'source'        => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
            'next_followup_at' => 'nullable|date',
        ]);

        $data['user_id'] = $userId;

        $lead = PcLead::query()->create($data);

        return redirect()->route('dashboard.user.titan-leads.leads.show', $lead->id)
            ->with('success', 'Lead created');
    }

    public function show(int $lead)
    {
        $userId = (int) Auth::id();

        $lead = PcLead::query()->where('user_id', $userId)->findOrFail($lead);

        return view('titan-leads::leads.show', compact('lead'));
    }
}
