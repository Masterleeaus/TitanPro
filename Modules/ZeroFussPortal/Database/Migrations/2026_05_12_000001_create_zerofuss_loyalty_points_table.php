<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zerofuss_loyalty_points', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('points');
            $table->string('direction', 20)->default('earn');
            $table->string('reason')->nullable();
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'customer_id']);
            $table->index(['company_id', 'customer_id', 'direction']);
            $table->unique(['company_id', 'customer_id', 'source_type', 'source_id', 'direction'], 'zerofuss_loyalty_unique_source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zerofuss_loyalty_points');
    }
};
