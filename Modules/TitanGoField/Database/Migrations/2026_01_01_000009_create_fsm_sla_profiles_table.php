<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_sla_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->string('vertical', 50)->nullable();
            $table->unsignedInteger('arrival_minutes')->default(240);
            $table->unsignedInteger('completion_minutes')->default(480);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'vertical']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_sla_profiles');
    }
};
