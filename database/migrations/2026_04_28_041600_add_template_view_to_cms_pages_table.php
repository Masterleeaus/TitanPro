<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cms_pages') && ! Schema::hasColumn('cms_pages', 'template_view')) {
            Schema::table('cms_pages', function (Blueprint $table): void {
                $table->string('template_view')->nullable()->after('website_content');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cms_pages') && Schema::hasColumn('cms_pages', 'template_view')) {
            Schema::table('cms_pages', function (Blueprint $table): void {
                $table->dropColumn('template_view');
            });
        }
    }
};
