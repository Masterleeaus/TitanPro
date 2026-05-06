<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_module_audit_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            // actor is either a numeric user_id string or the literal "system"
            $table->string('actor', 64)->index();
            $table->string('action', 40)->index(); // sync | enable | disable
            $table->string('module', 120)->index();
            $table->string('outcome', 20)->default('success'); // success | failure
            $table->json('context')->nullable(); // optional extra data
            $table->timestamp('created_at')->useCurrent();
            // Intentionally no updated_at — the log is append-only.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_module_audit_log');
    }
};
