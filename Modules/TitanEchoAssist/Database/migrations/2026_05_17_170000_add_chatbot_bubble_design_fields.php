<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ext_chatbots')) {
            return;
        }

        Schema::table('ext_chatbots', function (Blueprint $table) {
            if (! Schema::hasColumn('ext_chatbots', 'bubble_design')) {
                $table->string('bubble_design')->default('modern')->after('bubble_message');
            }
            if (! Schema::hasColumn('ext_chatbots', 'header_bg')) {
                $table->string('header_bg')->default('color')->after('color_mode');
            }
            if (! Schema::hasColumn('ext_chatbots', 'show_date_time')) {
                $table->boolean('show_date_time')->default(true)->after('show_logo');
            }
            if (! Schema::hasColumn('ext_chatbots', 'show_avg_response_time')) {
                $table->boolean('show_avg_response_time')->default(true)->after('show_date_time');
            }
            if (! Schema::hasColumn('ext_chatbots', 'header_bg_gradient_start')) {
                $table->string('header_bg_gradient_start')->nullable()->after('header_bg_color');
            }
            if (! Schema::hasColumn('ext_chatbots', 'header_bg_gradient_end')) {
                $table->string('header_bg_gradient_end')->nullable()->after('header_bg_gradient_start');
            }
            if (! Schema::hasColumn('ext_chatbots', 'welcome_bg_image')) {
                $table->string('welcome_bg_image')->nullable()->after('header_bg_image');
            }
            if (! Schema::hasColumn('ext_chatbots', 'promo_banner_image')) {
                $table->string('promo_banner_image')->nullable()->after('welcome_bg_image');
            }
            if (! Schema::hasColumn('ext_chatbots', 'promo_banner_title')) {
                $table->string('promo_banner_title')->nullable()->after('promo_banner_image');
            }
            if (! Schema::hasColumn('ext_chatbots', 'promo_banner_description')) {
                $table->text('promo_banner_description')->nullable()->after('promo_banner_title');
            }
            if (! Schema::hasColumn('ext_chatbots', 'promo_banner_cta_label')) {
                $table->string('promo_banner_cta_label')->nullable()->after('promo_banner_description');
            }
            if (! Schema::hasColumn('ext_chatbots', 'promo_banner_cta_url')) {
                $table->string('promo_banner_cta_url')->nullable()->after('promo_banner_cta_label');
            }
            if (! Schema::hasColumn('ext_chatbots', 'social_whatsapp')) {
                $table->string('social_whatsapp')->nullable()->after('promo_banner_cta_url');
            }
            if (! Schema::hasColumn('ext_chatbots', 'social_telegram')) {
                $table->string('social_telegram')->nullable()->after('social_whatsapp');
            }
            if (! Schema::hasColumn('ext_chatbots', 'social_facebook')) {
                $table->string('social_facebook')->nullable()->after('social_telegram');
            }
            if (! Schema::hasColumn('ext_chatbots', 'social_instagram')) {
                $table->string('social_instagram')->nullable()->after('social_facebook');
            }
            if (! Schema::hasColumn('ext_chatbots', 'footer_links')) {
                $table->json('footer_links')->nullable()->after('social_instagram');
            }
            if (! Schema::hasColumn('ext_chatbots', 'privacy_policy_url')) {
                $table->string('privacy_policy_url')->nullable()->after('footer_links');
            }
            if (! Schema::hasColumn('ext_chatbots', 'terms_url')) {
                $table->string('terms_url')->nullable()->after('privacy_policy_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ext_chatbots')) {
            return;
        }

        Schema::table('ext_chatbots', function (Blueprint $table) {
            $columns = [
                'bubble_design',
                'header_bg',
                'show_date_time',
                'show_avg_response_time',
                'header_bg_gradient_start',
                'header_bg_gradient_end',
                'welcome_bg_image',
                'promo_banner_image',
                'promo_banner_title',
                'promo_banner_description',
                'promo_banner_cta_label',
                'promo_banner_cta_url',
                'social_whatsapp',
                'social_telegram',
                'social_facebook',
                'social_instagram',
                'footer_links',
                'privacy_policy_url',
                'terms_url',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('ext_chatbots', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
