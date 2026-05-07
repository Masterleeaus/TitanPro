<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('item_job_type')) {
            Schema::create('item_job_type', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->cascadeOnDelete();
                $table->foreignId('job_type_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['item_id', 'job_type_id']);
            });
        }

        Schema::table('job_type_checklist_items', function (Blueprint $table) {
            if (! Schema::hasColumn('job_type_checklist_items', 'required_override')) {
                $table->boolean('required_override')->default(false)->after('is_required');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_type_checklist_items', function (Blueprint $table) {
            if (Schema::hasColumn('job_type_checklist_items', 'required_override')) {
                $table->dropColumn('required_override');
            }
        });

        Schema::dropIfExists('item_job_type');
    }
};
