<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_permits')) {
    Schema::create('work_permits', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->string('permit_ref', 80)->nullable();
        $table->string('label', 200);
        $table->string('permit_type', 60)->default('access');
        $table->string('status', 40)->default('active');
        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id'], 'idx_work_permits_tenant');
        $table->unique(['company_id','user_id','permit_ref'], 'uq_work_permits_ref');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_permits');

    }
};
