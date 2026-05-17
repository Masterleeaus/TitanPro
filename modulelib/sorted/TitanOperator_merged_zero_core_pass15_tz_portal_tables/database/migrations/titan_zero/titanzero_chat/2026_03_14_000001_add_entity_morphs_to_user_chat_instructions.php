<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TitanZero Phase 7 — Memory Entity Scoping
 *
 * Adds nullable polymorphic entity pair to user_chat_instructions so that
 * persistent memory can be scoped to TitanZero business objects
 * (jobs, clients, campaigns) rather than only to chat categories.
 *
 * Status: STUB — uncomment and run when entity scoping is ready.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Uncomment when ready to scope memory to TitanZero entities:
        //
        // Schema::table('user_chat_instructions', function (Blueprint $table) {
        //     $table->nullableMorphs('entity'); // adds entity_type + entity_id
        //     $table->index(['entity_type', 'entity_id'], 'uci_entity_index');
        // });
    }

    public function down(): void
    {
        // Schema::table('user_chat_instructions', function (Blueprint $table) {
        //     $table->dropMorphs('entity');
        // });
    }
};
