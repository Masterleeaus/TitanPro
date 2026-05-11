<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_pipeline_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('source')->nullable();
            $table->string('stage')->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_pipeline_entries');
    }
};
