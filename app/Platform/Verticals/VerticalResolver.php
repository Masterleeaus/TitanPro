<?php

namespace App\Platform\Verticals;

use Illuminate\Http\Request;

class VerticalResolver
{
    public function __construct(private readonly VerticalPackRegistry $registry) {}

    public function resolve(mixed $context = null, ?string $module = null): ?string
    {
        $resolved = $this->extractVertical($context);

        if ($resolved !== null) {
            return $this->registry->find($resolved)['key'] ?? null;
        }

        if ($module === null) {
            return null;
        }

        $default = $this->registry->default($module);

        if ($default === null) {
            return null;
        }

        return $this->registry->find($default)['key'] ?? null;
    }

    private function extractVertical(mixed $context): ?string
    {
        if (is_string($context) && $context !== '') {
            return $context;
        }

        if ($context instanceof Request) {
            $candidates = [
                $context->route('vertical'),
                $context->input('vertical'),
                $context->input('active_vertical'),
                $context->header('X-Titan-Vertical'),
            ];

            foreach ($candidates as $candidate) {
                if (is_string($candidate) && $candidate !== '') {
                    return $candidate;
                }
            }

            return null;
        }

        if (! is_array($context)) {
            return null;
        }

        $candidates = [
            $context['vertical'] ?? null,
            $context['active_vertical'] ?? null,
            $context['tenant']['vertical'] ?? null,
            $context['request']['vertical'] ?? null,
            $context['request']['route']['vertical'] ?? null,
            $context['request']['query']['vertical'] ?? null,
            $context['request']['headers']['X-Titan-Vertical'] ?? null,
        ];

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && $candidate !== '') {
                return $candidate;
            }
        }

        return null;
    }
}
