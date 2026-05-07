<?php

namespace App\Http\Controllers;

use App\Models\UiOverride;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Handles persistence of Visual UI Inspector component overrides.
 *
 * Routes (all require auth):
 *   GET  /titan/ui-inspector/overrides          → index
 *   POST /titan/ui-inspector/overrides          → upsert
 *   DELETE /titan/ui-inspector/overrides/{key}  → reset one component
 *   DELETE /titan/ui-inspector/overrides        → reset all for org
 */
class UiInspectorController extends Controller
{
    /** Return all overrides for the current user's organisation. */
    public function index(Request $request): JsonResponse
    {
        $orgId = $this->orgId($request);

        return response()->json(UiOverride::allForOrg($orgId));
    }

    /** Create or update overrides for a single component. */
    public function upsert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'component_key' => ['required', 'string', 'max:255'],
            'properties'    => ['required', 'array'],
            'properties.*'  => ['string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $override = UiOverride::upsertForComponent(
            componentKey: $request->input('component_key'),
            properties: $request->input('properties'),
            organizationId: $this->orgId($request),
            userId: $request->user()?->id ? (int) $request->user()->id : null,
        );

        return response()->json($override);
    }

    /** Remove the override for a single component so it reverts to defaults. */
    public function reset(Request $request, string $key): JsonResponse
    {
        UiOverride::resetComponent($key, $this->orgId($request));

        return response()->json(['reset' => true]);
    }

    /** Remove ALL overrides for the current organisation. */
    public function resetAll(Request $request): JsonResponse
    {
        UiOverride::where('organization_id', $this->orgId($request))->delete();

        return response()->json(['reset_all' => true]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function orgId(Request $request): ?int
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return isset($user->organization_id) ? (int) $user->organization_id : null;
    }
}
