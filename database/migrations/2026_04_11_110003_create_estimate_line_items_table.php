<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estimate_line_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estimate_package_id')
                ->constrained('estimate_packages')
                ->cascadeOnDelete();

            $table->foreignId('item_id')
                ->nullable()
                ->constrained('items')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('quantity', 10, 2)->default(1);

            // SQLite-safe replacement
            $table->decimal('total', 10, 2)->default(0);

            $table->boolean('is_taxable')->default(true);

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimate_line_items');
    }
};
