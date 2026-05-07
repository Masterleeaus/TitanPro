<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_locations', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('user_id');
            $table->index('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
        });

        DB::table('driver_locations')
            ->select('id', 'user_id')
            ->orderBy('id')
            ->chunkById(100, function ($locations): void {
                $organizationIds = DB::table('users')
                    ->whereIn('id', $locations->pluck('user_id')->filter()->unique())
                    ->pluck('organization_id', 'id');

                foreach ($locations as $location) {
                    $organizationId = $organizationIds[$location->user_id] ?? null;

                    if ($organizationId === null) {
                        continue;
                    }

                    DB::table('driver_locations')
                        ->where('id', $location->id)
                        ->update(['organization_id' => $organizationId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('driver_locations', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
