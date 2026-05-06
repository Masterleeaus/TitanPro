<?php

return [
    'module' => 'accountings',
    'agent' => 'titanzero.money',
    'boundary' => 'accounting_invoicing_followup_only_no_payment_execution',
    'capabilities' => [
        [
            'key' => 'accountings.help.explain_page',
            'label' => 'Accountings: Explain this page',
            'risk' => 'low',
            'requires' => [],
            'handler' => 'titanzero.intent.explain_page',
            'voice_phrases' => ['what is this page', 'explain this', 'help me'],
        ],
        [
            'key' => 'accountings.dashboard',
            'label' => 'Accountings: Dashboard',
            'risk' => 'low',
            'requires' => [],
            'handler' => 'accountings.dashboard',
            'voice_phrases' => ['dashboard', 'finance overview', 'accounting dashboard'],
        ],
        [
            'key' => 'accountings.cashflow.receivables',
            'label' => 'Accountings: Receivables',
            'risk' => 'low',
            'requires' => [],
            'handler' => 'cashflow.receivables',
            'voice_phrases' => ['receivables', 'overdue invoices', 'money owed'],
        ],
        [
            'key' => 'accountings.reconciliation.suggest',
            'label' => 'Accountings: Suggest Reconciliation Record',
            'risk' => 'medium',
            'requires' => ['accounting.reconcile'],
            'handler' => 'accounting.match_reconciliation_record',
            'voice_phrases' => ['suggest reconciliation', 'match accounting record', 'review bank reconciliation'],
        ],
        [
            'key' => 'accountings.invoice.journal.post',
            'label' => 'Accountings: Post Invoice Journal',
            'risk' => 'medium',
            'requires' => ['accounting.create'],
            'handler' => 'accounting.post_invoice_journal',
            'voice_phrases' => ['post invoice journal', 'record invoice revenue'],
        ],
    ],
    'go_enabled' => true,
    'zero_enabled' => true,
];
