<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TitanZeroChat — Fix Known Gap #3
 * Adds composite index on user_tiptap_contents morph columns for query performance.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_tiptap_contents', function (Blueprint $table) {
            if (Schema::hasTable('user_tiptap_contents')) {
                $table->index(
                    ['save_contentable_type', 'save_contentable_id'],
                    'utc_contentable_index'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_tiptap_contents', function (Blueprint $table) {
            $table->dropIndex('utc_contentable_index');
        });
    }
};
