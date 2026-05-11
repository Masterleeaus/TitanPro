<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estimate_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('id');
            $table->index('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
        });

        DB::table('estimate_packages')
            ->whereNull('organization_id')
            ->update([
                'organization_id' => DB::raw('(select organization_id from estimates where estimates.id = estimate_packages.estimate_id)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('estimate_packages', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
