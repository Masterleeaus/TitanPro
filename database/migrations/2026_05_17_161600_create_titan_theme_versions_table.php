<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_theme_versions')) {
            return;
        }

        Schema::create('titan_theme_versions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('org_id')->index();
            $table->string('panel', 64)->index();
            $table->unsignedInteger('version_number');
            $table->json('token_snapshot');
            $table->string('label')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['org_id', 'panel', 'version_number'], 'titan_theme_versions_org_panel_version_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_theme_versions');
    }
};
