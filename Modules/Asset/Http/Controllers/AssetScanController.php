<?php

namespace Modules\Asset\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetHistory;
use Modules\Asset\Entities\AssetMaintenance;
use Modules\Asset\Entities\AssetSetting;
use Modules\Asset\Entities\AssetTransaction;

class AssetScanController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            abort_403(! in_array(AssetSetting::MODULE_NAME, $this->user->modules));
            $this->pageTitle = __('asset::app.menu.asset');

            return $next($request);
        });
    }

    public function show(Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'view');

        $this->asset = Asset::with(['assetType', 'latestHistory'])->findOrFail($asset->id);

        return view('asset::asset.scan', $this->data);
    }

    public function issue(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'issued_to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $asset->status = 'lent';
        $asset->save();

        $payload = [
            'asset_id' => $asset->id,
            'user_id' => $data['issued_to_user_id'] ?? auth()->id(),
            'date_given' => Carbon::now(),
            'notes' => $data['note'] ?? null,
        ];

        $companyId = $this->currentCompanyId();
        if (Schema::hasColumn('asset_lending_history', 'company_id') && $companyId !== null) {
            $payload['company_id'] = $companyId;
        }

        AssetHistory::create($payload);

        return back()->with('success', 'Equipment issued.');
    }

    public function returnAsset(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'location' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! empty($data['location']) && Schema::hasColumn('assets', 'location')) {
            $asset->location = $data['location'];
        }
        $asset->status = 'available';
        $asset->save();

        $query = AssetHistory::where('asset_id', $asset->id)->whereNull('date_of_return')->orderByDesc('id');
        $companyId = $this->currentCompanyId();
        if (Schema::hasColumn('asset_lending_history', 'company_id') && $companyId !== null) {
            $query->where('company_id', $companyId);
        }

        $history = $query->first();
        if ($history) {
            $history->date_of_return = Carbon::now();
            $history->notes = trim(($history->notes ?? '').PHP_EOL.($data['note'] ?? ''));
            $history->save();
        }

        return back()->with('success', 'Equipment returned.');
    }

    public function report(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'status' => ['required', 'in:damaged,lost,non_functional,non-functional'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $asset->status = $data['status'] === 'non_functional' ? 'non-functional' : $data['status'];
        $asset->save();

        if (! empty($data['note'])) {
            $payload = [
                'asset_id' => $asset->id,
                'user_id' => auth()->id(),
                'date_given' => Carbon::now(),
                'date_of_return' => Carbon::now(),
                'notes' => '['.$asset->status.'] '.$data['note'],
            ];

            $companyId = $this->currentCompanyId();
            if (Schema::hasColumn('asset_lending_history', 'company_id') && $companyId !== null) {
                $payload['company_id'] = $companyId;
            }

            AssetHistory::create($payload);
        }

        return back()->with('success', 'Report saved.');
    }

    public function sendToMaintenance(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $payload = [
            'asset_id' => $asset->id,
            'status' => 'open',
            'priority' => 'medium',
            'details' => $data['reason'] ?? 'Sent to maintenance from scan.',
            'created_by' => auth()->id(),
        ];

        $companyId = $this->currentCompanyId();
        if (Schema::hasColumn('asset_maintenances', 'company_id') && $companyId !== null) {
            $payload['company_id'] = $companyId;
        }

        AssetMaintenance::create($payload);

        $asset->status = 'under-maintenance';
        $asset->save();

        return back()->with('success', 'Sent to maintenance.');
    }

    public function completeMaintenance(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $query = AssetMaintenance::where('asset_id', $asset->id)
            ->where('status', 'open')
            ->orderByDesc('id');

        $companyId = $this->currentCompanyId();
        if (Schema::hasColumn('asset_maintenances', 'company_id') && $companyId !== null) {
            $query->where('company_id', $companyId);
        }

        $maintenance = $query->first();
        if ($maintenance) {
            $maintenance->status = 'completed';
            if (! empty($data['note'])) {
                $maintenance->details = trim(($maintenance->details ?? '').PHP_EOL.$data['note']);
            }
            $maintenance->save();
        }

        $asset->status = 'available';
        $asset->save();

        return back()->with('success', 'Maintenance completed.');
    }

    public function allocate(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'allocated_to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $companyId = $this->currentCompanyId() ?? 0;

        AssetTransaction::create([
            'company_id' => $companyId,
            'asset_id' => $asset->id,
            'transaction_type' => 'allocate',
            'ref_no' => $this->buildTransactionRef('ALLOC', $asset->id),
            'receiver' => $data['allocated_to_user_id'] ?? auth()->id(),
            'quantity' => 1,
            'transaction_datetime' => Carbon::now(),
            'allocated_upto' => null,
            'reason' => $data['note'] ?? null,
            'parent_id' => null,
            'created_by' => auth()->id(),
        ]);

        $asset->status = 'lent';
        $asset->save();

        return back()->with('success', 'Equipment allocated.');
    }

    public function revokeAllocation(Request $request, Asset $asset)
    {
        $asset = $this->resolveTenantAsset($asset, 'update');
        $data = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $companyId = $this->currentCompanyId() ?? 0;

        $parent = AssetTransaction::where('asset_id', $asset->id)
            ->where('transaction_type', 'allocate')
            ->where('company_id', $companyId)
            ->latest('id')
            ->first();

        AssetTransaction::create([
            'company_id' => $companyId,
            'asset_id' => $asset->id,
            'transaction_type' => 'revoke_allocation',
            'ref_no' => $this->buildTransactionRef('REVOKE', $asset->id),
            'receiver' => null,
            'quantity' => 1,
            'transaction_datetime' => Carbon::now(),
            'allocated_upto' => null,
            'reason' => $data['note'] ?? null,
            'parent_id' => $parent?->id,
            'created_by' => auth()->id(),
        ]);

        $asset->status = 'available';
        $asset->save();

        return back()->with('success', 'Allocation revoked.');
    }

    private function resolveTenantAsset(Asset $asset, string $ability): Asset
    {
        $companyId = $this->currentCompanyId();
        if ($companyId !== null && (int) $asset->company_id !== $companyId) {
            abort(404);
        }

        $this->authorize($ability, $asset);

        return $asset;
    }

    private function currentCompanyId(): ?int
    {
        if (auth()->check() && isset(auth()->user()->company_id)) {
            return (int) auth()->user()->company_id;
        }

        if (auth()->check() && isset(auth()->user()->organization_id)) {
            return (int) auth()->user()->organization_id;
        }

        return null;
    }

    private function buildTransactionRef(string $prefix, int $assetId): string
    {
        return sprintf('ASSET-%s-%d-%s', $prefix, $assetId, Carbon::now()->format('YmdHis'));
    }
}
