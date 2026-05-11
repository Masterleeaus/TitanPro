<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ext_quotemaker_builders')) {
            Schema::create('ext_quotemaker_builders', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('mode')->default('quote');
                $table->string('vertical')->nullable();
                $table->string('service_type')->nullable();
                $table->string('variation')->nullable();
                $table->string('theme')->nullable();
                $table->string('visual_mode')->nullable();
                $table->string('package_tier')->nullable();
                $table->text('summary')->nullable();
                $table->string('pricing_model')->nullable();
                $table->integer('follow_up_delay_hours')->nullable();
                $table->string('follow_up_channel')->nullable();
                $table->boolean('auto_create_booking')->default(false);
                $table->boolean('auto_create_job')->default(false);
                $table->boolean('auto_send_invoice')->default(false);
                $table->boolean('auto_negotiate')->default(false);
                $table->longText('metadata_json')->nullable();
                $table->string('status')->default('draft');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_quotemaker_builders');
    }
};
