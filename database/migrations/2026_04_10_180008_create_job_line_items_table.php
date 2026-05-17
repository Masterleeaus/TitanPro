<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_line_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                ->constrained('field_jobs')
                ->cascadeOnDelete();

            $table->foreignId('item_id')
                ->nullable()
                ->constrained('items')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('quantity', 10, 2)->default(1);

            // SQLite-compatible replacement for stored/generated column
            $table->decimal('total', 10, 2)->default(0);

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_line_items');
    }
};
