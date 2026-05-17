<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titandocs_wizard_sessions')) {
            Schema::create('titandocs_wizard_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('doc_kind')->default('doc'); // doc | swms
                $table->unsignedTinyInteger('current_step')->default(1);
                $table->string('status')->default('draft'); // draft|complete|abandoned
                $table->json('payload_json')->nullable();
                $table->timestamps();

                $table->index(['company_id', 'user_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titandocs_wizard_sessions');
    }
};
