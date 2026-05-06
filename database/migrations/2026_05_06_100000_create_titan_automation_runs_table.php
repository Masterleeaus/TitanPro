<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_automation_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('automation_id', 191)->index();
            $table->string('trigger', 191)->index();
            $table->string('handler', 191)->nullable();
            $table->string('status', 40)->default('queued')->index();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->unsignedSmallInteger('max_attempts')->default(3);
            $table->json('payload')->nullable();
            $table->json('output')->nullable();
            $table->text('exception')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'attempts']);
            $table->index(['company_id', 'automation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_automation_runs');
    }
};
