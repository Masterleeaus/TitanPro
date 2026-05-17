<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('security_cleaner_sites')) {
            Schema::create('security_cleaner_sites', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('site_code')->nullable()->index();
                $table->string('name')->index();
                $table->string('address')->nullable();
                $table->string('supervisor_name')->nullable();
                $table->string('supervisor_phone')->nullable();
                $table->boolean('active')->default(true)->index();
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('security_cleaners')) {
            Schema::create('security_cleaners', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('cleaner_code')->unique();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->unsignedBigInteger('site_id')->nullable()->index();
                $table->string('vendor_name')->nullable()->index();
                $table->string('site_name')->nullable()->index();
                $table->string('status')->default('pending')->index();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('last_check_in_at')->nullable();
                $table->timestamp('last_check_out_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('security_cleaner_site_logs')) {
            Schema::create('security_cleaner_site_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('cleaner_id')->index();
                $table->unsignedBigInteger('site_id')->nullable()->index();
                $table->string('site_name')->nullable()->index();
                $table->string('checkpoint')->nullable();
                $table->timestamp('checked_in_at')->nullable();
                $table->timestamp('checked_out_at')->nullable();
                $table->unsignedBigInteger('checked_in_by')->nullable()->index();
                $table->unsignedBigInteger('checked_out_by')->nullable()->index();
                $table->string('status')->default('open')->index();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        foreach (['tr_access_card', 'tr_workpermits', 'tr_in_out_permit'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (! Schema::hasColumn($tableName, 'cleaner_id')) {
                        $table->unsignedBigInteger('cleaner_id')->nullable()->index()->after('id');
                    }
                    if (! Schema::hasColumn($tableName, 'site_id')) {
                        $table->unsignedBigInteger('site_id')->nullable()->index()->after('cleaner_id');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['tr_access_card', 'tr_workpermits', 'tr_in_out_permit'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $columns = [];
                    if (Schema::hasColumn($tableName, 'cleaner_id')) {
                        $columns[] = 'cleaner_id';
                    }
                    if (Schema::hasColumn($tableName, 'site_id')) {
                        $columns[] = 'site_id';
                    }
                    if ($columns !== []) {
                        $table->dropColumn($columns);
                    }
                });
            }
        }

        Schema::dropIfExists('security_cleaner_site_logs');
        Schema::dropIfExists('security_cleaners');
        Schema::dropIfExists('security_cleaner_sites');
    }
};
