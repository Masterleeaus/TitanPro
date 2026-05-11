<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\UiOverride;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Handles persistence of Visual UI Inspector component overrides.
 *
 * Routes (all require auth):
 *   GET  /titan/ui-inspector/overrides          → index
 *   POST /titan/ui-inspector/overrides          → upsert
 *   GET  /titan/ui-inspector/export             → export
 *   POST /titan/ui-inspector/import             → import
 *   DELETE /titan/ui-inspector/overrides/{key}  → reset one component
 *   DELETE /titan/ui-inspector/overrides        → reset all for org
 */
class UiInspectorController extends Controller
{
    /** @var array<int, string> */
    private const ALLOWED_PROPERTY_KEYS = [
        'padding',
        'margin',
        'border-radius',
        'box-shadow',
        'background-color',
        'color',
        'font-size',
        'font-weight',
        '--gradient',
        '--glass',
        '--animation',
    ];

    /** Return all overrides for the current user's organisation. */
    public function index(Request $request): JsonResponse
    {
        $orgId = $this->orgId($request);

        return response()->json(UiOverride::allForOrg($orgId));
    }

    /** Download all overrides for the current organisation as JSON. */
    public function export(Request $request): StreamedResponse
    {
        $orgId = $this->orgId($request);
        $orgName = Organization::query()->whereKey($orgId)->value('name') ?? 'organization';
        $orgSlug = Str::slug((string) $orgName);
        $filename = sprintf(
            'ui-overrides-%s-%s.json',
            $orgSlug !== '' ? $orgSlug : 'organization',
            now()->toDateString(),
        );
        $payload = UiOverride::allForOrg($orgId);
        ksort($payload);
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return response()->streamDownload(
            static function () use ($json): void {
                echo $json === false ? '{}' : $json;
            },
            $filename,
            ['Content-Type' => 'application/json'],
        );
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

        $errors = $this->validateOverridePayload([
            (string) $request->input('component_key') => $request->input('properties'),
        ]);

        if ($errors !== []) {
            return response()->json(['errors' => $errors], 422);
        }

        $override = UiOverride::upsertForComponent(
            componentKey: $request->input('component_key'),
            properties: $request->input('properties'),
            organizationId: $this->orgId($request),
            userId: $request->user()?->id ? (int) $request->user()->id : null,
        );

        return response()->json($override);
    }

    /** Import and upsert overrides for the current organisation. */
    public function import(Request $request): JsonResponse
    {
        $payload = $this->importPayload($request);

        if ($payload === null || $this->isListArray($payload)) {
            return response()->json(['errors' => ['payload' => ['Invalid JSON payload.']]], 422);
        }

        $errors = $this->validateOverridePayload($payload);

        if ($errors !== []) {
            return response()->json(['errors' => $errors], 422);
        }

        $orgId = $this->orgId($request);
        $userId = $request->user()?->id ? (int) $request->user()->id : null;

        foreach ($payload as $componentKey => $properties) {
            UiOverride::upsertForComponent(
                componentKey: (string) $componentKey,
                properties: $properties,
                organizationId: $orgId,
                userId: $userId,
            );
        }

        return response()->json(['imported' => count($payload)]);
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

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, array<int, string>>
     */
    private function validateOverridePayload(array $payload): array
    {
        $errors = [];

        foreach ($payload as $componentKey => $properties) {
            if (! is_string($componentKey) || trim($componentKey) === '' || mb_strlen($componentKey) > 255) {
                $errors["{$componentKey}"][] = 'Component key must be a non-empty string with max 255 chars.';
                continue;
            }

            if (! is_array($properties) || $this->isListArray($properties)) {
                $errors["{$componentKey}"][] = 'Properties must be an object keyed by CSS property name.';
                continue;
            }

            $unknown = array_diff(array_keys($properties), self::ALLOWED_PROPERTY_KEYS);
            if ($unknown !== []) {
                $errors["{$componentKey}"][] = 'Unknown property keys: '.implode(', ', $unknown);
            }

            foreach ($properties as $property => $value) {
                if (! is_string($value)) {
                    $errors["{$componentKey}.{$property}"][] = 'Value must be a string.';
                    continue;
                }

                if (! $this->isSafeCssValue($value)) {
                    $errors["{$componentKey}.{$property}"][] = 'Value is not a valid CSS override.';
                }
            }
        }

        return $errors;
    }

    private function isSafeCssValue(string $value): bool
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return true;
        }

        if (mb_strlen($trimmed) > 500) {
            return false;
        }

        if (preg_match('/[{};<>]/', $trimmed) === 1) {
            return false;
        }

        if (preg_match('/(?:javascript:|expression\s*\(|url\s*\()/i', $trimmed) === 1) {
            return false;
        }

        return preg_match('/^[-#%(),.\/\w\s:+]*$/u', $trimmed) === 1;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function importPayload(Request $request): ?array
    {
        $upload = $request->file('file');

        if ($upload !== null) {
            $decoded = json_decode((string) $upload->get(), true);

            return is_array($decoded) ? $decoded : null;
        }

        $decoded = json_decode($request->getContent(), true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param  array<mixed>  $value
     */
    private function isListArray(array $value): bool
    {
        return array_keys($value) === range(0, count($value) - 1);
    }
}
