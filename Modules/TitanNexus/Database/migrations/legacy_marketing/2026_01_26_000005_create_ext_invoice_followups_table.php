<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ext_invoice_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('invoice_ref')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->nullable()->index();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('amount_due', 12, 2)->nullable();
            $table->string('currency')->nullable();
            $table->date('due_date')->nullable()->index();
            $table->string('status')->default('pending')->index(); // pending|reminded|overdue|paid|disputed|cancelled
            $table->timestamp('last_reminded_at')->nullable();
            $table->timestamp('next_followup_at')->nullable()->index();
            $table->json('rules')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_invoice_followups');
    }
};
