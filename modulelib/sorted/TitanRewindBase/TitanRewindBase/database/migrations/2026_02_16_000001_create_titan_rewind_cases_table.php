<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('boxed_automation_cases')) return;

        Schema::create('boxed_automation_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->string('title')->nullable();
            $table->string('status', 30)->default('open');
            $table->string('severity', 30)->default('medium');

            $table->string('source_type', 80)->nullable();
            $table->bigInteger('source_id')->nullable();

            $table->timestamp('detected_at')->nullable();
            $table->json('meta_json')->nullable();

            $table->timestamp('resolved_at')->nullable();
            $table->string('resolved_by_type', 20)->nullable();
            $table->bigInteger('resolved_by_id')->nullable();

            $table->timestamps();

            $table->index(['company_id','user_id','status']);
            $table->index(['company_id','user_id','detected_at']);
        });
    }

    public function down(): void
    {
        // non-destructive template
    }
};
