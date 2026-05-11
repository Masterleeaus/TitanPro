<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Calls the Anthropic Claude API to generate a complete design-system token
 * set from a natural-language description.
 *
 * Usage:
 *   $tokens = app(AiThemeGenerator::class)->generate('luxury dark fintech');
 */
class AiThemeGenerator
{
    private const MODEL = 'claude-sonnet-4-6';

    private const MAX_TOKENS = 512;

    private const SYSTEM_PROMPT = <<<'SYSTEM'
You are a professional UI/UX design system architect specialising in admin dashboards.
Your task is to generate a complete design system in JSON format based on a natural language description.

Return ONLY a valid JSON object with these exact keys — no markdown, no explanation, no code blocks:
{
  "primary_color":   "#hexcolor",
  "secondary_color": "#hexcolor",
  "accent_color":    "#hexcolor",
  "surface_color":   "#hexcolor",
  "sidebar_color":   "#hexcolor",
  "font_heading":    "Font Name",
  "font_body":       "Font Name",
  "heading_weight":  "600",
  "border_radius":   "8px",
  "shadow":          "0 4px 24px 0 rgba(0,0,0,0.18)",
  "button_hover":    "brightness(1.15)",
  "description":     "One-sentence description of this design system"
}

Design rules:
- All color values MUST be valid 6-digit hex codes (e.g. #B8860B, #0F0F14).
- Fonts must be real Google Fonts or standard system fonts (Inter, Figtree, Poppins, Roboto, DM Sans, Lato, Nunito, etc.).
- heading_weight must be one of: "300", "400", "500", "600", "700", "800", "900".
- border_radius must be a CSS length like "4px", "6px", "8px", "12px", "16px", or "9999px".
- shadow must be a valid CSS box-shadow value.
- button_hover must be a CSS filter function, e.g. "brightness(1.1)" or "brightness(0.9)".
- Dark themes: use very dark surface (#0d0d14 range), dark sidebar, vibrant or metallic primary accent.
- Light themes: use white/near-white surfaces, clean font, coloured primary.
- Ensure good contrast between surface and primary for accessibility.
SYSTEM;

    /**
     * Generate design-system tokens from a natural-language prompt.
     *
     * @return array<string, string>
     *
     * @throws \RuntimeException when the API is not configured, returns an error, or returns invalid JSON.
     */
    public function generate(string $prompt): array
    {
        $apiKey = config('services.anthropic.api_key');

        if (empty($apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY is not configured. Please add it to your .env file.');
        }

        $response = Http::withHeaders([
            'x-api-key'         => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model'      => self::MODEL,
            'max_tokens' => self::MAX_TOKENS,
            'system'     => self::SYSTEM_PROMPT,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if (! $response->successful()) {
            Log::error('AiThemeGenerator: API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \RuntimeException(
                'AI theme generation failed (HTTP ' . $response->status() . '). Please try again.'
            );
        }

        $data    = $response->json();
        $content = $data['content'][0]['text'] ?? '';

        if ($content === '') {
            throw new \RuntimeException('AI returned an empty response. Please try again.');
        }

        return $this->parseTokens($content);
    }

    /**
     * Parse the raw text returned by Claude into a validated token array.
     *
     * @return array<string, string>
     *
     * @throws \RuntimeException when the text cannot be decoded as JSON.
     */
    private function parseTokens(string $raw): array
    {
        // Strip markdown code fences if Claude adds them despite instructions.
        $clean = (string) preg_replace('/^```(?:json)?\s*/m', '', $raw);
        $clean = (string) preg_replace('/\s*```\s*$/m', '', $clean);
        $clean = trim($clean);

        $parsed = json_decode($clean, true);

        if (! is_array($parsed)) {
            Log::warning('AiThemeGenerator: JSON parse failed', ['raw' => $raw]);
            throw new \RuntimeException('AI returned invalid JSON. Please try again.');
        }

        $hexRegex = '/^#[0-9a-fA-F]{6}$/';

        return [
            'primary_color'   => preg_match($hexRegex, $parsed['primary_color'] ?? '') ? $parsed['primary_color'] : '#2563eb',
            'secondary_color' => preg_match($hexRegex, $parsed['secondary_color'] ?? '') ? $parsed['secondary_color'] : '#0f172a',
            'accent_color'    => preg_match($hexRegex, $parsed['accent_color'] ?? '') ? $parsed['accent_color'] : '#14b8a6',
            'surface_color'   => preg_match($hexRegex, $parsed['surface_color'] ?? '') ? $parsed['surface_color'] : '#f8fafc',
            'sidebar_color'   => preg_match($hexRegex, $parsed['sidebar_color'] ?? '') ? $parsed['sidebar_color'] : '#1e293b',
            'font_heading'    => $this->sanitizeFont($parsed['font_heading'] ?? 'Inter'),
            'font_body'       => $this->sanitizeFont($parsed['font_body'] ?? 'Inter'),
            'heading_weight'  => $this->sanitizeWeight($parsed['heading_weight'] ?? '600'),
            'border_radius'   => $this->sanitizeRadius($parsed['border_radius'] ?? '8px'),
            'shadow'          => $this->sanitizeShadow($parsed['shadow'] ?? '0 4px 24px 0 rgba(0,0,0,0.18)'),
            'button_hover'    => $this->sanitizeButtonHover($parsed['button_hover'] ?? 'brightness(1.1)'),
            'description'     => mb_substr(strip_tags((string) ($parsed['description'] ?? '')), 0, 200),
        ];
    }

    private function sanitizeFont(string $font): string
    {
        return preg_match('/^[\w\s\-]+$/', $font) ? $font : 'Inter';
    }

    private function sanitizeWeight(string $weight): string
    {
        return in_array($weight, ['300', '400', '500', '600', '700', '800', '900'], true)
            ? $weight
            : '600';
    }

    private function sanitizeRadius(string $radius): string
    {
        return preg_match('/^\d+(?:\.\d+)?px$/', $radius) ? $radius : '8px';
    }

    private function sanitizeShadow(string $shadow): string
    {
        // Allow characters typical in CSS box-shadow values.
        if (preg_match('/^[\d\s\-.,a-zA-Z()%\/]+$/', $shadow)) {
            return $shadow;
        }

        return '0 4px 24px 0 rgba(0,0,0,0.18)';
    }

    private function sanitizeButtonHover(string $value): string
    {
        return preg_match('/^brightness\([\d.]+\)$/', $value) ? $value : 'brightness(1.1)';
    }
}
