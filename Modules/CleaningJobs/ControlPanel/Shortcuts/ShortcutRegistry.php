<?php

namespace Modules\CleaningJobs\ControlPanel\Shortcuts;

/**
 * ShortcutRegistry defines quick actions available from the TitanWork control panel.
 *
 * Each shortcut corresponds to an action that can be triggered via the AI layer
 * or a simple button on the UI. Linking these to handlers allows the
 * operator to quickly perform common tasks without navigating multiple pages.
 */
class ShortcutRegistry
{
    /**
     * Return an array of registered shortcuts.
     *
     * Each shortcut should include a unique key, a human friendly label and
     * the fully qualified action class that implements the shortcut logic.
     */
    public static function getShortcuts(): array
    {
        return [
            [
                'key' => 'create_job',
                'label' => 'Create Job',
                'action' => \Modules\CleaningJobs\Actions\CreateJob::class,
            ],
            [
                'key' => 'create_request',
                'label' => 'Create Request',
                'action' => \Modules\CleaningJobs\Actions\CreateRequest::class,
            ],
            [
                'key' => 'generate_checklist',
                'label' => 'Generate Checklist',
                'action' => \Modules\CleaningJobs\Actions\GenerateChecklist::class,
            ],
            // TODO: add more shortcuts as needed
        ];
    }
}