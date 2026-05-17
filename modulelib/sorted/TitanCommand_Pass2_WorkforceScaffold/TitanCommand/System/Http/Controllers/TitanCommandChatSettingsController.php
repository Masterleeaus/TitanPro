<?php

namespace App\Extensions\TitanCommand\System\Http\Controllers;

use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TitanCommandChatSettingsController extends Controller
{

    private function tenantIds(): array
    {
        $userId = (int) (\Illuminate\Support\Facades\Auth::id() ?? 0);
        // MVP rule: company_id == user_id
        $companyId = $userId;
        return [$companyId, $userId];
    }

    public function index()
    {
        return view('titancommand::chat.settings.index');
    }

    public function update(Request $request): RedirectResponse
    {
        if (Helper::appIsNotDemo()) {
            $suggestions = collect($request->input('input_name', []))
                ->zip($request->input('input_prompt', []))
                ->map(fn ($pair) => [
                    'name'   => trim($pair[0] ?? ''),
                    'prompt' => trim($pair[1] ?? ''),
                ])
                ->filter(fn ($item) => $item['name'] && $item['prompt'])
                ->values()
                ->all();

            $encodedSuggestions = json_encode($suggestions, JSON_THROW_ON_ERROR);

            setting([
                'titancommand_example_prompts' => $encodedSuggestions,
            ])->save();

            Setting::forgetCache();
        }

        return back()->with(['message' => __('Updated Successfully'), 'type' => 'success']);
    }
}
