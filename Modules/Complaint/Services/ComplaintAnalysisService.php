<?php

namespace Modules\Complaint\Services;

class ComplaintAnalysisService
{
    /**
     * @return array{severity: string, category: string, resolution_suggestion: string}
     */
    public function analyse(string $subject, string $description = ''): array
    {
        $text = strtolower(trim($subject . ' ' . $description));

        $severity = 'medium';
        if (str_contains($text, 'urgent') || str_contains($text, 'immediately') || str_contains($text, 'danger')) {
            $severity = 'urgent';
        } elseif (str_contains($text, 'bad') || str_contains($text, 'unhappy') || str_contains($text, 'refund')) {
            $severity = 'high';
        } elseif (str_contains($text, 'minor') || str_contains($text, 'small')) {
            $severity = 'low';
        }

        $category = 'general';
        if (str_contains($text, 'refund') || str_contains($text, 'billing') || str_contains($text, 'invoice')) {
            $category = 'billing';
        } elseif (str_contains($text, 'late') || str_contains($text, 'delay')) {
            $category = 'service-delay';
        } elseif (str_contains($text, 'clean') || str_contains($text, 'quality')) {
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
}
