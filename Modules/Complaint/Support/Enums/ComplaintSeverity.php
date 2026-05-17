<?php

namespace Modules\Complaint\Support\Enums;

enum ComplaintSeverity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public static function fromAnalysis(string $severity): self
    {
        return match (strtolower($severity)) {
            'low' => self::LOW,
            'medium' => self::MEDIUM,
            'high' => self::HIGH,
            'urgent', 'critical' => self::URGENT,
            default => self::MEDIUM,
        };
    }
}
