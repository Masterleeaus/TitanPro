<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Backfill migration: add company_id tenant boundary to EInvoice core tables.
 *
 * Tables affected: einvoice_invoices, einvoice_invoice_items,
 *                  einvoice_ai_notes, einvoice_ai_drafts
 *
 * Blueprint ref: 19-TENANCY-IDENTITY-BOUNDARY-BLUEPRINT.md
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- einvoice_invoices ---
        if (Schema::hasTable('einvoice_invoices') && ! Schema::hasColumn('einvoice_invoices', 'company_id')) {
            Schema::table('einvoice_invoices', function (Blueprint $table): void {
                $table->unsignedBigInteger('company_id')->nullable()->default(0)->index()->after('id');
                $table->index(['company_id', 'status'], 'einvoice_invoices_company_status_idx');
                $table->index(['company_id', 'due_date'], 'einvoice_invoices_company_due_date_idx');
            });
        }

        // --- einvoice_invoice_items ---
        if (Schema::hasTable('einvoice_invoice_items') && ! Schema::hasColumn('einvoice_invoice_items', 'company_id')) {
            Schema::table('einvoice_invoice_items', function (Blueprint $table): void {
                $table->unsignedBigInteger('company_id')->nullable()->default(0)->index()->after('id');
            });
        }

        // --- einvoice_ai_notes ---
        if (Schema::hasTable('einvoice_ai_notes') && ! Schema::hasColumn('einvoice_ai_notes', 'company_id')) {
            Schema::table('einvoice_ai_notes', function (Blueprint $table): void {
                $table->unsignedBigInteger('company_id')->nullable()->default(0)->index()->after('id');
            });
        }

        // --- einvoice_ai_drafts ---
        if (Schema::hasTable('einvoice_ai_drafts') && ! Schema::hasColumn('einvoice_ai_drafts', 'company_id')) {
            Schema::table('einvoice_ai_drafts', function (Blueprint $table): void {
                $table->unsignedBigInteger('company_id')->nullable()->default(0)->index()->after('id');
            });
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive — dropping company_id columns would
        // destroy tenant-scoping and is irreversible.
    }
};
