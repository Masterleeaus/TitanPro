<?php

namespace Modules\EInvoice\UI\Tabs;

final class ControlPanelTabs
{
    public static function make(): array
    {
        return [
            ['key'=>'overview','label'=>'Overview','description'=>'Invoice status, receivables, overdue risk, cashflow snapshot, and GST visibility.','widgets'=>['invoice_total','outstanding_receivables','overdue_risk','cashflow_snapshot','gst_snapshot']],
            ['key'=>'invoices','label'=>'Invoices','description'=>'Drafts, sent invoices, recurring invoices, templates, XML exports, and auto-send rules.','tables'=>['invoices','drafts','recurring_invoices','invoice_templates','xml_exports']],
            ['key'=>'followups','label'=>'Late Follow-Ups','description'=>'Overdue invoices, reminder ladder, escalation queue, follow-up history, and payment-plan notes.','tables'=>['overdue_invoices','followup_timeline','reminder_campaigns','escalation_queue','payment_plan_notes']],
            ['key'=>'receivables','label'=>'Receivables','description'=>'A/R aging, customer balances, statements, promised-payment notes, and invoice risk.','tables'=>['ar_aging','customer_balances','customer_statements','receivable_risk']],
            ['key'=>'accounting','label'=>'Accounting','description'=>'Journal entries, chart of accounts, payables, bank reconciliation records, vendor statements, and ledger sync.','tables'=>['journal_entries','chart_of_accounts','payables','bank_reconciliations','vendor_statements']],
            ['key'=>'compliance','label'=>'Compliance','description'=>'GST reports, tax summaries, audit logs, export packs, and invoice compliance artifacts.','tables'=>['gst_reports','tax_summaries','audit_logs','export_packs']],
            ['key'=>'ai_control','label'=>'AI Control','description'=>'TitanZero Money tools, knowledge packs, embeddings, prompt policies, signals, and approval rules.','tables'=>['agents','tools','knowledge_packs','embeddings','prompt_policies','signal_hooks']],
        ];
    }
}
