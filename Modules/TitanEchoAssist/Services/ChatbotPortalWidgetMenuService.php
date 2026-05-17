<?php

namespace Modules\TitanEchoAssist\Services;

class ChatbotPortalWidgetMenuService
{
    public function menu(): array
    {
        return [
            'sections' => [
                [
                    'key' => 'overview',
                    'items' => [
                        ['key' => 'home', 'label' => 'Home', 'path' => 'portal/home'],
                        ['key' => 'dashboard', 'label' => 'Dashboard', 'path' => 'portal/dashboard'],
                    ],
                ],
                [
                    'key' => 'services',
                    'items' => [
                        ['key' => 'visits', 'label' => 'Visits', 'path' => 'portal/visits'],
                        ['key' => 'bookings', 'label' => 'Bookings', 'path' => 'portal/bookings'],
                        ['key' => 'recurring', 'label' => 'Recurring', 'path' => 'portal/recurring'],
                        ['key' => 'issues', 'label' => 'Issues', 'path' => 'portal/issues'],
                    ],
                ],
                [
                    'key' => 'billing',
                    'items' => [
                        ['key' => 'invoices', 'label' => 'Invoices', 'path' => 'portal/invoices'],
                        ['key' => 'documents', 'label' => 'Documents', 'path' => 'portal/documents'],
                    ],
                ],
                [
                    'key' => 'support',
                    'items' => [
                        ['key' => 'actions', 'label' => 'Actions', 'path' => 'portal/actions'],
                        ['key' => 'notifications', 'label' => 'Notifications', 'path' => 'portal/notifications'],
                        ['key' => 'feedback', 'label' => 'Feedback', 'path' => 'portal/feedback'],
                    ],
                ],
            ],
        ];
    }
}
