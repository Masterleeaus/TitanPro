<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create Titan Operator Avatars table.
     *
     * IMPORTANT:
     * - Do NOT query this table inside this migration.
     * - Only create/drop it, otherwise fresh installs will fail.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ext_titan_operator_avatars')) {
            Schema::create('ext_titan_operator_avatars', function (Blueprint $table) {
                $table->id();

                // Basic avatar metadata
                $table->string('name')->nullable();
                $table->string('image')->nullable(); // path/url or stored filename

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_titan_operator_avatars');
    }
};