<?php

namespace App\Support\TitanOS\Widgets;

use Illuminate\Contracts\View\View;

/**
 * Render generic Titan OS widgets by mapping the widget type to a Blade
 * template.  Supported types include: card, metric, alert, list, table,
 * timeline, form_shell, action_button_shell and empty_state.  When an
 * unsupported type is encountered, an empty state view is rendered.
 */
class WidgetRenderer
{
    /**
     * Render a widget based on its schema.
     *
     * @param  array<string, mixed>  $widget
     * @return View
     */
    public static function render(array $widget): View
    {
        // Validate the widget before attempting to render it.  If validation
        // fails, treat it as an empty state to avoid runtime errors.
        if (! WidgetSchemaValidator::validate($widget)) {
            // If the widget schema is invalid or unsupported, render an empty state
            // with an explicit message so that the user knows the widget could
            // not be rendered.  This prevents runtime errors and avoids
            // silently failing.
            return view('titan-os.widgets.empty-state', ['widget' => ['message' => 'Unsupported widget']]);
        }

        $type = $widget['type'] ?? 'empty_state';
        $view = match ($type) {
            'card' => 'titan-os.widgets.card',
            'metric' => 'titan-os.widgets.metric',
            'alert' => 'titan-os.widgets.alert',
            'list' => 'titan-os.widgets.list',
            'table' => 'titan-os.widgets.table',
            'timeline' => 'titan-os.widgets.timeline',
            'form_shell' => 'titan-os.widgets.form-shell',
            'action_button_shell' => 'titan-os.widgets.action-button-shell',
            default => 'titan-os.widgets.empty-state',
        };

        return view($view, ['widget' => $widget]);
    }
}