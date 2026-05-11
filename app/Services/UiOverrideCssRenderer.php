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
     * Pattern for valid CSS property names:
     *   - Standard properties:   one or more letters/digits/hyphens, starting with a letter.
     *   - Custom properties:     exactly two leading hyphens followed by an ident.
     * Single-leading-hyphen vendor prefixes are not expected from user input
     * (internally generated properties like -webkit-backdrop-filter are added
     * directly without going through this validator).
     */
    private const SAFE_PROPERTY_RE = '/^(--[a-zA-Z][a-zA-Z0-9-]*|[a-zA-Z][a-zA-Z0-9-]*)$/';

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
                // Use CSS-specific escaping for the attribute value.
                $selector = '[data-ui-key="' . self::escapeCssString((string) $componentKey) . '"]';
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
     * Property names and values are sanitized before inclusion.
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
                $safe = self::sanitizeCssValue($value);
                if ($safe !== '') {
                    $declarations['background'] = $safe;
                }
            } elseif ($prop === '--glass') {
                // Value is expected to be a numeric blur amount (pixels).
                $blur = (float) $value;
                if ($blur > 0) {
                    $declarations['backdrop-filter']         = 'blur(' . $blur . 'px)';
                    $declarations['-webkit-backdrop-filter'] = 'blur(' . $blur . 'px)';
                    $declarations['background-color']        = 'rgba(255,255,255,0.15)';
                }
            } elseif ($prop === '--animation') {
                $transition = self::ANIMATION_CSS[$value] ?? '';
                if ($transition !== '') {
                    $declarations['transition'] = $transition;
                }
                if ($value === 'pulse') {
                    $declarations['animation'] = 'titanPulse 1.2s ease-in-out infinite alternate';
                }
            } else {
                if (! self::isSafeCssProperty($prop)) {
                    continue;
                }
                $safe = self::sanitizeCssValue($value);
                if ($safe !== '') {
                    $declarations[$prop] = $safe;
                }
            }
        }

        return $declarations;
    }

    /**
     * Return true when the property name is a syntactically valid CSS property
     * (standard or custom property starting with --).
     */
    private static function isSafeCssProperty(string $prop): bool
    {
        return (bool) preg_match(self::SAFE_PROPERTY_RE, $prop);
    }

    /**
     * Strip characters / sequences from a CSS property value that could be
     * used to escape the <style> block or inject arbitrary content.
     *
     * - Removes CSS comment syntax (prevents /* … * / tricks).
     * - Removes any `</` sequence to prevent early </style> tag closure
     *   regardless of case or surrounding characters.
     * - Removes bare backslashes (CSS escape sequences not needed for values
     *   generated by the inspector).
     *
     * NOTE: This is the primary sanitization boundary relied upon by the
     * `{!! $css !!}` unescaped output in ui-override-ssr.blade.php. Every
     * user-controlled value that ends up in the CSS string must pass through
     * this method (or be hardcoded from the ANIMATION_CSS constant).
     */
    private static function sanitizeCssValue(string $value): string
    {
        // Strip CSS comments (non-greedy so /* a */ /* b */ are both removed).
        $value = preg_replace('#/\*[^*]*(?:\*(?!/)[^*]*)*\*/#', '', $value) ?? $value;
        // Prevent any </…> tag injection by stripping the opening `</` sequence.
        $value = preg_replace('#</+#i', '', $value);
        // Remove backslashes (not expected in valid colour / length / gradient values).
        $value = str_replace('\\', '', $value);

        return trim($value);
    }

    /**
     * Escape a string for safe use inside a CSS attribute-selector value
     * (double-quoted).  Escapes backslashes and double-quote characters
     * according to CSS string-escaping rules.
     */
    private static function escapeCssString(string $value): string
    {
        // Escape backslashes first, then double-quotes.
        $value = str_replace('\\', '\\\\', $value);
        $value = str_replace('"', '\\"', $value);

        return $value;
    }
}
