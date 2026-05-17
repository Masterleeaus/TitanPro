<?php

namespace Modules\Complaint\Services;

class ComplaintAnalysisService
{
    private const URGENT_KEYWORDS = ['urgent', 'immediately', 'danger'];
    private const HIGH_KEYWORDS = ['bad', 'unhappy', 'refund'];
    private const LOW_KEYWORDS = ['minor', 'small'];
    private const BILLING_KEYWORDS = ['refund', 'billing', 'invoice'];
    private const DELAY_KEYWORDS = ['late', 'delay'];
    private const QUALITY_KEYWORDS = ['clean', 'quality'];

    /**
     * @return array{severity: string, category: string, resolution_suggestion: string}
     */
    public function analyse(string $subject, string $description = ''): array
    {
        $text = strtolower(trim($subject . ' ' . $description));

        $severity = 'medium';
        if ($this->containsAny($text, self::URGENT_KEYWORDS)) {
            $severity = 'urgent';
        } elseif ($this->containsAny($text, self::HIGH_KEYWORDS)) {
            $severity = 'high';
        } elseif ($this->containsAny($text, self::LOW_KEYWORDS)) {
            $severity = 'low';
        }

        $category = 'general';
        if ($this->containsAny($text, self::BILLING_KEYWORDS)) {
            $category = 'billing';
        } elseif ($this->containsAny($text, self::DELAY_KEYWORDS)) {
            $category = 'service-delay';
        } elseif ($this->containsAny($text, self::QUALITY_KEYWORDS)) {
            $category = 'service-quality';
        }

        $suggestion = match ($category) {
            'billing' => 'Review invoice details and offer adjustment or refund where policy permits.',
            'service-delay' => 'Acknowledge delay, explain cause, and provide a compensated recovery slot.',
            'service-quality' => 'Arrange a follow-up quality check and offer reclean if standards were missed.',
            default => 'Acknowledge the concern, investigate context, and provide a clear resolution timeline.',
        };

        return [
            'severity' => $severity,
            'category' => $category,
            'resolution_suggestion' => $suggestion,
        ];
    }

    /**
     * @return array{message: string, tone: string}
     */
    public function draftResponse(string $subject, string $resolutionSuggestion, string $tone = 'empathetic'): array
    {
        return [
            'message' => sprintf(
                'Thank you for raising this complaint about "%s". We are reviewing it now. %s',
                trim($subject) !== '' ? trim($subject) : 'your recent experience',
                $resolutionSuggestion
            ),
            'tone' => $tone,
        ];
    }

    /**
     * @param array<int, string> $needles
     */
    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
