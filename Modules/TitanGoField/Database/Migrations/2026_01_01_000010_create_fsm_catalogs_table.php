<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('vertical', 50)->nullable();
            $table->string('sku', 100)->nullable();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('unit', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'sku']);
        });

        Schema::create('fsm_rate_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('vertical', 50)->nullable();
            $table->string('code', 50)->nullable();
            $table->decimal('hourly', 12, 2)->default(0);
            $table->decimal('callout', 12, 2)->default(0);
            $table->decimal('after_hours_multiplier', 5, 2)->default(1.5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'vertical']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_rate_cards');
        Schema::dropIfExists('fsm_catalog_items');
    }
};
