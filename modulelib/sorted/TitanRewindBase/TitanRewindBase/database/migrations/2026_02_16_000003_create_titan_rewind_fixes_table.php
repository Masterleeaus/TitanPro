<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('boxed_automation_fixes')) return;

        Schema::create('boxed_automation_fixes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('case_id')->unsigned();

            $table->string('fix_type', 80)->default('manual');

            $table->string('proposed_by_type', 20)->default('user');
            $table->bigInteger('proposed_by_id')->nullable();

            $table->boolean('requires_confirmation')->default(true);
            $table->string('status', 30)->default('proposed');

            $table->json('proposal_json')->nullable();

            $table->string('confirm_token', 40)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmed_by_type', 20)->nullable();
            $table->bigInteger('confirmed_by_id')->nullable();

            $table->timestamp('applied_at')->nullable();
            $table->string('applied_by_type', 20)->nullable();
            $table->bigInteger('applied_by_id')->nullable();

            $table->json('result_json')->nullable();
            $table->text('error_text')->nullable();

            $table->timestamps();

            $table->index(['company_id','user_id','case_id','status']);
        });
    }

    public function down(): void
    {
        // non-destructive template
    }
};
