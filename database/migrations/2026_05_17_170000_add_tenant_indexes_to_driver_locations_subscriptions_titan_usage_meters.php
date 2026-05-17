<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add composite (organization_id, created_at) index for efficient tenant-scoped
        // queries ordered by time.  driver_locations already has an organization_id
        // column (added in a prior migration) but no composite time-ordered index.
        Schema::table('driver_locations', function (Blueprint $table) {
            $table->index(['organization_id', 'created_at'], 'driver_locations_org_created_at_index');
        });

        // subscriptions already has (organization_id, status) index; add time-ordered one.
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['organization_id', 'created_at'], 'subscriptions_org_created_at_index');
        });

        // titan_usage_meters already has (organization_id, meter_key) index; add time-ordered one.
        Schema::table('titan_usage_meters', function (Blueprint $table) {
            $table->index(['organization_id', 'created_at'], 'titan_usage_meters_org_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('driver_locations', function (Blueprint $table) {
            $table->dropIndex('driver_locations_org_created_at_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_org_created_at_index');
        });

        Schema::table('titan_usage_meters', function (Blueprint $table) {
            $table->dropIndex('titan_usage_meters_org_created_at_index');
        });
    }
};
