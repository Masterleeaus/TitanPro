<?php

namespace App\Support\TitanOS\Widgets;

/**
 * Validate widget schemas produced by Titan Zero before rendering.  This
 * validator performs minimal checks in this pass, ensuring that at least the
 * `type` key exists.  In future passes, stricter validation can be added to
 * enforce required fields for each widget type.
 */
class WidgetSchemaValidator
{
    /**
     * Determine whether the provided widget schema is valid.
     *
     * @param  array<string, mixed>  $schema
     * @return bool
     */
    public static function validate(array $schema): bool
    {
        // A widget must declare a type
        if (! isset($schema['type']) || ! is_string($schema['type'])) {
            return false;
        }

        $type = $schema['type'];
        $allowed = [
            'card',
            'metric',
            'alert',
            'list',
            'table',
            'timeline',
            'form_shell',
            'action_button_shell',
            'empty_state',
        ];

        if (! in_array($type, $allowed, true)) {
            return false;
        }

        // Required field checks per widget type
        $required = [
            // Cards must provide both a title and content so the renderer can
            // display a heading and the body text.
            'card' => ['title', 'content'],
            'metric' => ['label', 'value'],
            'alert' => ['message'],
            'list' => ['items'],
            'table' => ['columns', 'rows'],
            'timeline' => ['items'],
            'form_shell' => ['fields'],
            // Action button shells must provide both a label and an actionKey so the
            // renderer can display the button text and emit the correct event.
            'action_button_shell' => ['label', 'actionKey'],
            // Empty state should include at least a title or message but this is
            // not strictly enforced here; fallback will be used if none provided.
        ];
        if (isset($required[$type])) {
            foreach ($required[$type] as $field) {
                if (! array_key_exists($field, $schema)) {
                    return false;
                }
            }
        }

        return true;
    }
}