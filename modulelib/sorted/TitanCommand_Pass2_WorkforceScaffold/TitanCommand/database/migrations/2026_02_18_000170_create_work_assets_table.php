<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_assets')) {
    Schema::create('work_assets', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->string('asset_ref', 80)->nullable();
        $table->string('label', 200);
        $table->string('asset_type', 60)->default('equipment');
        $table->string('status', 40)->default('active');
        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id'], 'idx_work_assets_tenant');
        $table->unique(['company_id','user_id','asset_ref'], 'uq_work_assets_ref');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_assets');

    }
};
