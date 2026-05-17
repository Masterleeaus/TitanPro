<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('saas_packages')) {
            Schema::create('saas_packages', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('billing_interval')->default('monthly');
                $table->unsignedInteger('interval_count')->default(1);
                $table->unsignedInteger('trial_days')->default(0);
                $table->decimal('price', 12, 2)->default(0);
                $table->string('currency', 3)->default('AUD');
                $table->unsignedInteger('location_limit')->nullable();
                $table->unsignedInteger('user_limit')->nullable();
                $table->unsignedInteger('product_limit')->nullable();
                $table->unsignedInteger('invoice_limit')->nullable();
                $table->json('features')->nullable();
                $table->json('custom_permissions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_private')->default(false);
                $table->boolean('is_popular')->default(false);
                $table->unsignedInteger('sort_order')->default(0);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('saas_coupons')) {
            Schema::create('saas_coupons', function (Blueprint $table): void {
                $table->id();
                $table->string('code')->unique();
                $table->string('discount_type')->default('percentage');
                $table->decimal('discount_value', 12, 2)->default(0);
                $table->dateTime('starts_at')->nullable();
                $table->dateTime('expires_at')->nullable();
                $table->unsignedInteger('usage_limit')->nullable();
                $table->unsignedInteger('used_count')->default(0);
                $table->json('applies_to_plans')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('platform_communicator_logs')) {
            Schema::create('platform_communicator_logs', function (Blueprint $table): void {
                $table->id();
                $table->string('channel')->default('email');
                $table->string('audience')->nullable();
                $table->string('subject')->nullable();
                $table->longText('message')->nullable();
                $table->string('status')->default('draft');
                $table->unsignedInteger('recipient_count')->default(0);
                $table->json('metadata')->nullable();
                $table->dateTime('sent_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_communicator_logs');
        Schema::dropIfExists('saas_coupons');
        Schema::dropIfExists('saas_packages');
    }
};
