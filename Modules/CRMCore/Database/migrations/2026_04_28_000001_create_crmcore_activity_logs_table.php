<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('crmcore_activity_logs')) {
            Schema::create('crmcore_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('actor_id')->nullable()->index();
                $table->string('subject_type')->nullable()->index();
                $table->unsignedBigInteger('subject_id')->nullable()->index();
                $table->string('event')->index();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('deals')) {
            Schema::table('deals', function (Blueprint $table) {
                if (! Schema::hasColumn('deals', 'crmcore_project_id')) {
                    $table->unsignedBigInteger('crmcore_project_id')->nullable()->index();
                }

                if (! Schema::hasColumn('deals', 'crmcore_service_interest')) {
                    $table->string('crmcore_service_interest')->nullable();
                }

                if (! Schema::hasColumn('deals', 'crmcore_converted_to_project_at')) {
                    $table->timestamp('crmcore_converted_to_project_at')->nullable();
                }
            });
        }

        if (Schema::hasTable('leads') && ! Schema::hasColumn('leads', 'crmcore_score')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->unsignedTinyInteger('crmcore_score')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'crmcore_score')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('crmcore_score');
            });
        }

        if (Schema::hasTable('deals')) {
            Schema::table('deals', function (Blueprint $table) {
                foreach (['crmcore_project_id', 'crmcore_service_interest', 'crmcore_converted_to_project_at'] as $column) {
                    if (Schema::hasColumn('deals', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('crmcore_activity_logs');
    }
};
