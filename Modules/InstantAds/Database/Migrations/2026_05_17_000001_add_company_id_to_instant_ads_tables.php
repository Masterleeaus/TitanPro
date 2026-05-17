<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_image_pro') && ! Schema::hasColumn('ai_image_pro', 'company_id')) {
            Schema::table('ai_image_pro', function (Blueprint $table): void {
                $table->unsignedBigInteger('company_id')->default(1)->after('id')->index();
            });
        }

        if (Schema::hasTable('ai_image_pro_likes') && ! Schema::hasColumn('ai_image_pro_likes', 'company_id')) {
            Schema::table('ai_image_pro_likes', function (Blueprint $table): void {
                $table->unsignedBigInteger('company_id')->default(1)->after('id')->index();
            });
        }

    }

    public function down(): void
    {
        if (Schema::hasTable('ai_image_pro') && Schema::hasColumn('ai_image_pro', 'company_id')) {
            Schema::table('ai_image_pro', function (Blueprint $table): void {
                $table->dropColumn('company_id');
            });
        }

        if (Schema::hasTable('ai_image_pro_likes') && Schema::hasColumn('ai_image_pro_likes', 'company_id')) {
            Schema::table('ai_image_pro_likes', function (Blueprint $table): void {
                $table->dropColumn('company_id');
            });
        }
    }
};
