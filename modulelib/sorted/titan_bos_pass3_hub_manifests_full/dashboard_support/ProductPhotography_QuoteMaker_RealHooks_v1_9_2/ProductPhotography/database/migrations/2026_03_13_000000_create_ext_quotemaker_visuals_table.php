<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ext_quotemaker_visuals', function (Blueprint $table) {
            $table->id();
            $table->string('service_type')->nullable();
            $table->string('site_type')->nullable();
            $table->string('work_area')->nullable();
            $table->text('scope_notes')->nullable();
            $table->string('visual_mode')->default('quote_preview');
            $table->string('package_tier')->nullable();
            $table->string('quote_reference')->nullable();
            $table->text('customer_context')->nullable();
            $table->longText('generated_prompt')->nullable();
            $table->longText('render_payload_json')->nullable();
            $table->string('result_title')->nullable();
            $table->text('result_summary')->nullable();
            $table->string('usage_tag')->nullable();
            $table->string('image_path')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_quotemaker_visuals');
    }
};
