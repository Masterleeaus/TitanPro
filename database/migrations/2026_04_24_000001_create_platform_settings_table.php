<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();

            $table->string('app_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('logo')->nullable();

            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('accent_color')->nullable();

            // Missing column fix
            $table->string('surface_color')->nullable();

            $table->string('font_heading')->nullable();
            $table->string('font_body')->nullable();

            $table->string('support_email')->nullable();
            $table->text('footer_text')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->string('marketing_headline')->nullable();
            $table->string('marketing_subheadline')->nullable();

            $table->boolean('enable_registration')->default(true);

            $table->timestamps();
        });

        DB::table('platform_settings')->insert([
            'app_name' => 'TITAN ZERO',
            'logo_path' => 'platform/titan-zero-logo.png',
            'logo' => 'platform/titan-zero-logo.png',
            'primary_color' => '#2563eb',
            'secondary_color' => '#0f172a',
            'accent_color' => '#14b8a6',
            'surface_color' => '#f8fafc',
            'font_heading' => 'Figtree',
            'font_body' => 'Figtree',
            'support_email' => 'support@titanzero.pro',
            'footer_text' => 'Powered by Titan Zero.',
            'meta_title' => 'TITAN ZERO',
            'meta_description' => 'Titan Zero field operations platform.',
            'marketing_headline' => 'Field operations, controlled from one hub.',
            'marketing_subheadline' => 'Manage jobs, teams, invoices, dispatch, and SaaS tenants from Titan Zero.',
            'enable_registration' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
