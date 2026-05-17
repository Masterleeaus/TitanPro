<?php

namespace Modules\TitanEchoAssist\Services;

class ChatbotPortalWidgetMenuService
{
    public function build(array $context): array
    {
        $firstName = (string) ($context['first_name'] ?? 'there');
        $nextVisitLabel = (string) ($context['next_visit_label'] ?? "You're all caught up for now");

        $menu = [
            'hero' => [
                'title' => sprintf('Hi %s 👋', $firstName),
                'subtitle' => sprintf('Your next clean is %s', $nextVisitLabel),
                'primary_actions' => [
                    ['label' => 'Book Visit', 'type' => 'portal_action', 'value' => 'book_visit'],
                    ['label' => 'Pay Invoice', 'type' => 'portal_action', 'value' => 'pay_invoice'],
                ],
            ],
            'customer' => [
                'name' => (string) ($context['customer_name'] ?? 'Customer'),
                'email' => (string) ($context['customer_email'] ?? ''),
                'properties' => $context['properties'] ?? [],
            ],
            'tabs' => ['Home', 'Chat', 'Help'],
            'sections' => [
                [
                    'title' => 'Bookings',
                    'items' => [
                        ['label' => 'Book a Visit', 'type' => 'portal_action', 'value' => 'book_visit'],
                        ['label' => 'Reschedule', 'type' => 'chat_prompt', 'value' => 'I need to reschedule my next visit'],
                        ['label' => 'Skip Next Visit', 'type' => 'portal_action', 'value' => 'skip_next_visit'],
                        ['label' => 'Pause Service', 'type' => 'portal_action', 'value' => 'pause_service'],
                    ],
                ],
                [
                    'title' => 'Quotes',
                    'items' => [
                        ['label' => 'View Quotes', 'type' => 'screen', 'value' => 'quotes'],
                        ['label' => 'Approve Quote', 'type' => 'portal_action', 'value' => 'approve_quote'],
                        ['label' => 'Request Changes', 'type' => 'chat_prompt', 'value' => "I'd like to request changes to my quote"],
                    ],
                ],
                [
                    'title' => 'Invoices',
                    'items' => [
                        ['label' => 'View Invoices', 'type' => 'screen', 'value' => 'invoices'],
                        ['label' => 'Pay Now', 'type' => 'portal_action', 'value' => 'pay_invoice'],
                        ['label' => 'Download Receipt', 'type' => 'portal_action', 'value' => 'download_receipt'],
                    ],
                ],
                [
                    'title' => 'Feedback',
                    'items' => [
                        ['label' => 'Rate Last Visit', 'type' => 'portal_action', 'value' => 'rate_visit'],
                        ['label' => 'Request Re-clean', 'type' => 'portal_action', 'value' => 'request_reclean'],
                    ],
                ],
                [
                    'title' => 'My Property',
                    'items' => [
                        ['label' => 'Access Instructions', 'type' => 'screen', 'value' => 'site_profile'],
                        ['label' => 'Pets & Parking', 'type' => 'screen', 'value' => 'site_profile'],
                    ],
                ],
                [
                    'title' => 'Visit History',
                    'items' => [
                        ['label' => 'Past Visits', 'type' => 'screen', 'value' => 'visits'],
                        ['label' => 'View Checklist', 'type' => 'portal_action', 'value' => 'view_checklist'],
                    ],
                ],
                [
                    'title' => 'Recurring Service',
                    'items' => [
                        ['label' => 'Change Frequency', 'type' => 'portal_action', 'value' => 'change_frequency'],
                        ['label' => 'Add Extras', 'type' => 'chat_prompt', 'value' => "I'd like to add extras to my regular service"],
                    ],
                ],
                [
                    'title' => 'Support',
                    'items' => [
                        ['label' => 'Report Issue', 'type' => 'chat_prompt', 'value' => 'I have an issue with my last clean'],
                        ['label' => 'Billing Help', 'type' => 'chat_prompt', 'value' => 'I have a billing question'],
                    ],
                ],
            ],
        ];

        $filters = [
            'pay_invoice' => (bool) ($context['show_pay_invoice'] ?? false),
            'approve_quote' => (bool) ($context['show_approve_quote'] ?? false),
            'request_reclean' => (bool) ($context['show_request_reclean'] ?? false),
            'rate_visit' => (bool) ($context['show_rate_visit'] ?? false),
        ];

        $menu['hero']['primary_actions'] = array_values(array_filter(
            $menu['hero']['primary_actions'],
            fn (array $item): bool => !isset($filters[$item['value']]) || $filters[$item['value']]
        ));

        $menu['sections'] = array_map(function (array $section) use ($filters): array {
            $section['items'] = array_values(array_filter(
                $section['items'],
                fn (array $item): bool => !isset($filters[$item['value']]) || $filters[$item['value']]
            ));

            return $section;
        }, $menu['sections']);

        return $menu;
    }
}
