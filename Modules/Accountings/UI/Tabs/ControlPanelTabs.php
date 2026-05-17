<?php

namespace Modules\Accountings\UI\Tabs;

final class ControlPanelTabs
{
    public static function make(): array
    {
        return [
            ['key'=>'overview','label'=>'Overview','description'=>'Invoice status, receivables, overdue risk, cashflow snapshot, and GST visibility.','widgets'=>['invoice_total','outstanding_receivables','overdue_risk','cashflow_snapshot','gst_snapshot']],
            ['key'=>'invoices','label'=>'Invoices','description'=>'Drafts, sent invoices, recurring invoices, templates, XML exports, and auto-send rules.','tables'=>['invoices','drafts','recurring_invoices','invoice_templates','xml_exports']],
            ['key'=>'payments','label'=>'Payments','description'=>'Payment sessions and transaction visibility sourced from integrated records.','tables'=>['payment_sessions','transactions']],
            ['key'=>'collections','label'=>'Collections','description'=>'Overdue invoices, reminder ladder, escalation queue, follow-up history, and payment-plan notes.','tables'=>['overdue_invoices','followup_timeline','reminder_campaigns','escalation_queue','payment_plan_notes']],
            ['key'=>'bank_matching','label'=>'Bank Matching','description'=>'Bank deposit and invoice matching workflows for reconciliation confidence.','tables'=>['bank_deposits','bank_reconciliations']],
            ['key'=>'accounting','label'=>'Accounting','description'=>'Journal entries, chart of accounts, payables, and ledger sync status.','tables'=>['journal_entries','chart_of_accounts','payables','vendor_statements']],
            ['key'=>'compliance','label'=>'Compliance','description'=>'GST reports, tax summaries, audit logs, export packs, and invoice compliance artifacts.','tables'=>['gst_reports','tax_summaries','audit_logs','export_packs']],
            ['key'=>'ai_control','label'=>'AI Control','description'=>'TitanZero Money tools, knowledge packs, embeddings, prompt policies, signals, and approval rules.','tables'=>['agents','tools','knowledge_packs','embeddings','prompt_policies','signal_hooks']],
        ];
    }
}
