<?php

namespace App\Http\Controllers;

use App\Support\ThemeCustomPresetStore;
use App\Support\ThemePresetManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThemePresetController
{
    public function apply(Request $request): RedirectResponse
    {
        $data = $request->validate(['preset' => ['required', 'string']]);
        ThemePresetManager::setActivePreset($data['preset']);

        return back()->with('status', 'Theme preset updated.');
    }

    public function duplicate(Request $request): RedirectResponse
    {
        $data = $request->validate(['preset' => ['required', 'string'], 'label' => ['nullable', 'string']]);
        ThemeCustomPresetStore::duplicate($data['preset'], $data['label'] ?? null);

        return back()->with('status', 'Theme preset duplicated.');
    }

    public function delete(Request $request): RedirectResponse
    {
        $data = $request->validate(['preset' => ['required', 'string']]);
        ThemeCustomPresetStore::delete($data['preset']);

        return back()->with('status', 'Custom preset deleted.');
    }

    public function export(Request $request): BinaryFileResponse
    {
        $preset = $request->query('preset');
        $path = ThemeCustomPresetStore::export(is_string($preset) ? $preset : null);

        return response()->download($path);
    }
}
