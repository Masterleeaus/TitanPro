<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('boxed_automation_actions')) return;

        Schema::create('boxed_automation_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->bigInteger('case_id')->unsigned()->nullable();
            $table->bigInteger('fix_id')->unsigned()->nullable();

            $table->string('action_type', 80)->default('action');
            $table->string('target_type', 120)->nullable();
            $table->bigInteger('target_id')->nullable();

            $table->json('before_json')->nullable();
            $table->json('after_json')->nullable();

            $table->string('executed_by_type', 20)->default('system');
            $table->bigInteger('executed_by_id')->nullable();
            $table->timestamp('executed_at')->nullable();

            $table->boolean('success')->default(false);
            $table->text('error_text')->nullable();

            $table->timestamps();

            $table->index(['company_id','user_id','executed_at']);
        });
    }

    public function down(): void
    {
        // non-destructive template
    }
};
