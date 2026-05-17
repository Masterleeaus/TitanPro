<?php

namespace App\Extensions\ProductPhotography\Services;

class PromptBuilder
{
    public function build(array $payload): string
    {
        $mode = $payload['visual_mode'] ?? 'quote_preview';
        $service = $payload['service_type'] ?? 'Service';
        $site = $payload['site_type'] ?? 'Property';
        $area = $payload['work_area'] ?? 'relevant work zones';
        $scope = $payload['scope_notes'] ?? 'practical service scope';
        $tier = $payload['package_tier'] ?? 'standard';

        $modeInstruction = match ($mode) {
            'before_after' => 'Show a believable before and after transformation with matched angle and realistic improvement.',
            'proposal_cover' => 'Create a polished proposal cover image with a premium trustworthy service-business tone.',
            'scope_visual' => 'Illustrate the work scope clearly, focusing on where work happens and what changes.',
            default => 'Create a practical quote preview image showing the expected completed result.',
        };

        return trim(implode(' ', [
            $modeInstruction,
            "Service: {$service}.",
            "Site: {$site}.",
            "Work area: {$area}.",
            "Package tier: {$tier}.",
            "Scope notes: {$scope}.",
            'Use realistic home or property environments, clean presentation, no abstract art, no fantasy styling.',
            'Make the outcome easy for a customer to trust and approve.',
        ]));
    }
}
