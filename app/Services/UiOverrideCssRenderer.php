<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UiOverride;
use App\Platform\Ui\ComponentRegistry;

/**
 * Generates an inline CSS block from saved UiOverrides so that component
 * overrides are applied server-side (eliminating the flash of unstyled
 * content that occurs when Alpine.js applies them after page load).
 *
 * Only CSS-property names are emitted; the special virtual properties
 * --gradient, --glass, and --animation are expanded to their real CSS
 * equivalents, mirroring the logic in public/js/titan/ui-inspector.js.
 */
final class UiOverrideCssRenderer
{
    /** Mirror of ANIMATION_CSS in public/js/titan/ui-inspector.js */
    private const ANIMATION_CSS = [
        'none'     => '',
        'fadeIn'   => 'opacity 0.4s ease',
        'slideUp'  => 'transform 0.4s ease, opacity 0.4s ease',
        'scaleIn'  => 'transform 0.3s ease',
        'bounceIn' => 'transform 0.5s cubic-bezier(.36,.07,.19,.97)',
        'pulse'    => 'transform 0.6s ease-in-out infinite alternate',
    ];

    /**
     * Generate an inline CSS block from the saved overrides for the given org.
     *
     * For each component_key that has an entry in ComponentRegistry the
     * ComponentRegistry selector is used.  Keys that are not in the registry
     * (e.g. data-ui-key values written by the Alpine inspector directly) fall
     * back to a [data-ui-key="…"] attribute selector so they are also
     * pre-rendered.
     *
     * @param  int|null  $orgId
     * @return string  CSS ready to embed in a <style> tag (may be empty)
     */
    public static function render(?int $orgId): string
    {
        $overrides = UiOverride::allForOrg($orgId);

        if (empty($overrides)) {
            return '';
        }

        $css = '';

        foreach ($overrides as $componentKey => $properties) {
            $component = ComponentRegistry::get($componentKey);

            if ($component !== null) {
                $selector = $component['selector'];
            } else {
                // Alpine inspector stores arbitrary element keys; fall back to
                // [data-ui-key] so they are still pre-rendered when possible.
                $escaped  = htmlspecialchars($componentKey, ENT_QUOTES, 'UTF-8');
                $selector = '[data-ui-key="' . $escaped . '"]';
            }

            $declarations = self::buildDeclarations($properties);

            if (empty($declarations)) {
                continue;
            }

            $css .= $selector . " {\n";
            foreach ($declarations as $prop => $value) {
                $css .= '    ' . $prop . ': ' . $value . ";\n";
            }
            $css .= "}\n";
        }

        return $css;
    }

    /**
     * Convert a properties map (as stored in UiOverride.properties) to CSS
     * declaration key → value pairs, expanding the virtual --gradient,
     * --glass, and --animation pseudo-properties to real CSS.
     *
     * @param  array<string, string>  $properties
     * @return array<string, string>
     */
    public static function buildDeclarations(array $properties): array
    {
        $declarations = [];

        foreach ($properties as $prop => $value) {
            // Skip empty values (but keep "0").
            if ($value === null || $value === '') {
                continue;
            }

            if ($prop === '--gradient') {
                $declarations['background'] = $value;
            } elseif ($prop === '--glass') {
                $declarations['backdrop-filter']         = 'blur(' . $value . 'px)';
                $declarations['-webkit-backdrop-filter'] = 'blur(' . $value . 'px)';
                $declarations['background-color']        = 'rgba(255,255,255,0.15)';
            } elseif ($prop === '--animation') {
                $transition = self::ANIMATION_CSS[$value] ?? '';
                if ($transition !== '') {
                    $declarations['transition'] = $transition;
                }
                if ($value === 'pulse') {
                    $declarations['animation'] = 'titanPulse 1.2s ease-in-out infinite alternate';
                }
            } else {
                $declarations[$prop] = $value;
            }
        }

        return $declarations;
    }
}
