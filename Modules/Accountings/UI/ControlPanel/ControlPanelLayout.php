<?php

namespace Modules\Accountings\UI\ControlPanel;

final class ControlPanelLayout
{
    public static function sections(): array
    {
        return [
            'header' => ['title', 'titanzero_money_status', 'quick_actions'],
            'main' => ['workspace_tabs', 'primary_table', 'records'],
            'side' => ['ai_agent', 'risk_flags', 'timeline'],
            'bottom' => ['audit', 'settings', 'integrations'],
        ];
    }

    public static function records(): array
    {
        return [
            'invoices',
            'drafts',
            'recurring_invoices',
            'invoice_templates',
            'xml_exports',
            'overdue_invoices',
            'followup_queue',
            'receivables',
            'journal_entries',
            'bank_reconciliations',
            'gst_reports',
            'audit_logs',
        ];
    }

    public static function metrics(): array
    {
        return [
            'invoice_total',
            'outstanding_receivables',
            'overdue_risk_score',
            'late_followups_due',
            'cashflow_runway',
            'gst_liability_snapshot',
        ];
    }

    public static function boundary(): array
    {
        return [
            'owns' => ['invoicing', 'accounting', 'receivables', 'gst', 'late_invoice_followup'],
            'does_not_own' => ['payment_sessions', 'payid_qr', 'card_processing', 'gateway_webhooks', 'settlements'],
            'payment_system' => 'external_zeropay',
        ];
    }
}
