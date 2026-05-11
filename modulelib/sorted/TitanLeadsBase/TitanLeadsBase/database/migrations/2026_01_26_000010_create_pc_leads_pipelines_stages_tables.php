<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pipelines
        if (!Schema::hasTable('ext_pc_pipelines')) {
            Schema::create('ext_pc_pipelines', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Stages
        if (!Schema::hasTable('ext_pc_stages')) {
            Schema::create('ext_pc_stages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('pipeline_id')->index();
                $table->string('name');
                $table->string('color')->nullable(); // UI hint only
                $table->integer('sort_order')->default(0);
                $table->boolean('is_won')->default(false);
                $table->boolean('is_lost')->default(false);
                $table->timestamps();
            });
        }

        // Leads (link to existing TitanLeads contacts)
        if (!Schema::hasTable('ext_pc_leads')) {
            Schema::create('ext_pc_leads', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('contact_id')->nullable()->index(); // TitanLeads contacts.id

                $table->unsignedBigInteger('pipeline_id')->index();
                $table->unsignedBigInteger('stage_id')->index();

                $table->string('lead_type')->nullable(); // property_manager, strata, facilities, airbnb, etc
                $table->string('company_name')->nullable();
                $table->string('person_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();

                $table->string('source')->nullable(); // built-in pack, import, manual, etc
                $table->text('notes')->nullable();

                $table->timestamp('last_contacted_at')->nullable();
                $table->timestamp('next_followup_at')->nullable();

                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_pc_leads');
        Schema::dropIfExists('ext_pc_stages');
        Schema::dropIfExists('ext_pc_pipelines');
    }
};
