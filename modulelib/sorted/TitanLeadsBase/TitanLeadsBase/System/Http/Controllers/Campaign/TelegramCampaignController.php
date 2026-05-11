<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Campaign;

use App\Extensions\TitanLeads\System\Http\Requests\Campaign\CampaignTelegramRequest;
use App\Extensions\TitanLeads\System\Models\MarketingCampaign;
use App\Extensions\TitanLeads\System\Models\Telegram\TelegramContact;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TelegramCampaignController extends Controller
{
    public function index()
    {
        return view('titan-leads::telegram-campaign.index', [
            'title' => 'Telegram Campaigns',
            'items' => MarketingCampaign::query()
                ->where('type', 'telegram')
                ->where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('titan-leads::telegram-campaign.create', [
            'title'           => trans('New Telegram Campaign'),
            'action'          => route('dashboard.user.titan-leads.telegram-campaign.store'),
            'method'          => 'POST',
            'item'            => new MarketingCampaign,
            'contacts'        => TelegramContact::query()->where('user_id', Auth::id())->get(),
            'selectedContact' => [],
        ]);
    }

    public function store(CampaignTelegramRequest $request): RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $data = $request->validated();

        MarketingCampaign::query()->create($data);

        return redirect()->route('dashboard.user.titan-leads.telegram-campaign.index')
            ->with([
                'type'    => 'success',
                'message' => trans('Telegram campaign created'),
            ]);
    }

    public function edit(MarketingCampaign $telegramCampaign): View
    {
        $this->authorize('edit', $telegramCampaign);

        return view('titan-leads::telegram-campaign.create', [
            'title'           => trans('Edit Telegram Campaign'),
            'action'          => route('dashboard.user.titan-leads.telegram-campaign.store'),
            'method'          => 'POST',
            'item'            => $telegramCampaign,
            'contacts'        => TelegramContact::query()->where('user_id', Auth::id())->get(),
            'selectedContact' => $telegramCampaign->getAttribute('contacts') ?: [],
        ]);
    }

    public function update(CampaignTelegramRequest $request, MarketingCampaign $telegramCampaign): RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $this->authorize('update', $telegramCampaign);

        $telegramCampaign->update($request->validated());

        return redirect()->route('dashboard.user.titan-leads.telegram-campaign.index')
            ->with([
                'type'    => 'success',
                'message' => trans('Telegram campaign updated'),
            ]);
    }

    public function destroy(MarketingCampaign $telegramCampaign): JsonResponse
    {
        $this->authorize('delete', $telegramCampaign);

        $telegramCampaign->delete();

        return response()->json([
            'status'  => 'success',
            'message' => trans('Telegram campaign deleted'),
        ]);
    }

    public function show(MarketingCampaign $telegramCampaign) {}
}
