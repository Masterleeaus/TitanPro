<?php

namespace Modules\Security\Security\Sanitization;

class InputSanitizer
{
    public function string(?string $value, int $maxLength = 255): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim(strip_tags($value));
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? $value;

        return mb_substr($value, 0, $maxLength);
    }

    public function filename(?string $value, int $maxLength = 180): string
    {
        $value = $this->string($value ?? 'file', $maxLength) ?? 'file';
        $value = preg_replace('/[^A-Za-z0-9._-]/', '_', $value) ?? 'file';
        $value = preg_replace('/_+/', '_', $value) ?? $value;
        $value = trim($value, '._-');

        return $value !== '' ? $value : 'file';
    }

    public function integer($value, int $min = 1): int
    {
        $value = filter_var($value, FILTER_VALIDATE_INT);
        if ($value === false || $value < $min) {
            throw new \InvalidArgumentException('Invalid integer value.');
        }

        return (int) $value;
    }
}
