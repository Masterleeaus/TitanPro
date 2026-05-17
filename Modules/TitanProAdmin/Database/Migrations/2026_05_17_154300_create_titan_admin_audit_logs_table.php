<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_admin_audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->unsignedBigInteger('target_company_id')->nullable();
            $table->string('action');
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['target_company_id', 'action']);
            $table->index(['actor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_admin_audit_logs');
    }
};
