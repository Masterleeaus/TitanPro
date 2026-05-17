<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ext_quotemaker_execution_logs')) {
            Schema::create('ext_quotemaker_execution_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('builder_id')->nullable();
                $table->string('mode')->nullable();
                $table->string('action')->nullable();
                $table->string('target_table')->nullable();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->string('status')->default('draft');
                $table->longText('payload_json')->nullable();
                $table->longText('result_json')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_quotemaker_execution_logs');
    }
};
