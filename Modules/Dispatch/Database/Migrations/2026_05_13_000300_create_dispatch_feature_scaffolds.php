<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('technician_profiles')) {
            Schema::create('technician_profiles', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('display_name')->nullable(); $table->string('phone')->nullable();
                $table->decimal('home_base_latitude', 10, 7)->nullable(); $table->decimal('home_base_longitude', 10, 7)->nullable();
                $table->unsignedBigInteger('default_zone_id')->nullable()->index(); $table->unsignedInteger('capacity_minutes_per_day')->default(480);
                $table->boolean('active')->default(true); $table->timestamps();
            });
        }
        if (! Schema::hasTable('technician_skills')) {
            Schema::create('technician_skills', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->string('name'); $table->text('description')->nullable(); $table->boolean('active')->default(true); $table->timestamps();
            });
        }
        if (! Schema::hasTable('technician_profile_skill')) {
            Schema::create('technician_profile_skill', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('technician_profile_id')->index(); $table->unsignedBigInteger('technician_skill_id')->index(); $table->string('level')->nullable(); $table->timestamps();
            });
        }
        if (! Schema::hasTable('service_zones')) {
            Schema::create('service_zones', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->string('name'); $table->string('code')->nullable()->index(); $table->text('description')->nullable(); $table->json('polygon')->nullable(); $table->decimal('center_latitude', 10, 7)->nullable(); $table->decimal('center_longitude', 10, 7)->nullable(); $table->boolean('active')->default(true); $table->timestamps();
            });
        }
        if (! Schema::hasTable('customer_locations')) {
            Schema::create('customer_locations', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('customer_id')->nullable()->index(); $table->string('name')->nullable(); $table->string('address_line_1')->nullable(); $table->string('address_line_2')->nullable(); $table->string('suburb')->nullable(); $table->string('state')->nullable(); $table->string('postcode')->nullable(); $table->string('country')->default('Australia'); $table->decimal('latitude', 10, 7)->nullable(); $table->decimal('longitude', 10, 7)->nullable(); $table->text('access_notes')->nullable(); $table->text('parking_notes')->nullable(); $table->unsignedBigInteger('service_zone_id')->nullable()->index(); $table->boolean('active')->default(true); $table->timestamps();
            });
        }
        if (! Schema::hasTable('dispatch_routes')) {
            Schema::create('dispatch_routes', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('technician_id')->nullable()->index(); $table->date('route_date')->index(); $table->string('name')->nullable(); $table->string('status')->default('draft')->index(); $table->unsignedInteger('total_distance_meters')->nullable(); $table->unsignedInteger('total_duration_seconds')->nullable(); $table->timestamp('started_at')->nullable(); $table->timestamp('completed_at')->nullable(); $table->json('metadata')->nullable(); $table->timestamps();
            });
        }
        if (! Schema::hasTable('dispatch_route_stops')) {
            Schema::create('dispatch_route_stops', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('dispatch_route_id')->index(); $table->unsignedBigInteger('work_order_id')->nullable()->index(); $table->unsignedBigInteger('appointment_id')->nullable()->index(); $table->unsignedBigInteger('customer_location_id')->nullable()->index(); $table->unsignedInteger('sequence')->default(1); $table->string('status')->default('planned')->index(); $table->timestamp('planned_arrival_at')->nullable(); $table->timestamp('planned_departure_at')->nullable(); $table->timestamp('actual_arrival_at')->nullable(); $table->timestamp('actual_departure_at')->nullable(); $table->unsignedInteger('travel_seconds_from_previous')->nullable(); $table->unsignedInteger('distance_meters_from_previous')->nullable(); $table->text('notes')->nullable(); $table->timestamps();
            });
        }
        if (! Schema::hasTable('travel_time_estimates')) {
            Schema::create('travel_time_estimates', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('origin_location_id')->nullable()->index(); $table->unsignedBigInteger('destination_location_id')->nullable()->index(); $table->unsignedInteger('distance_meters')->nullable(); $table->unsignedInteger('duration_seconds')->nullable(); $table->string('provider')->nullable(); $table->timestamp('calculated_at')->nullable(); $table->json('metadata')->nullable(); $table->timestamps();
            });
        }
        if (! Schema::hasTable('dispatch_status_logs')) {
            Schema::create('dispatch_status_logs', function (Blueprint $table): void {
                $table->id(); $table->unsignedInteger('company_id')->nullable()->index(); $table->unsignedBigInteger('assign_shift_id')->nullable()->index(); $table->unsignedBigInteger('work_order_id')->nullable()->index(); $table->unsignedBigInteger('appointment_id')->nullable()->index(); $table->string('from_status')->nullable(); $table->string('to_status')->index(); $table->unsignedBigInteger('changed_by')->nullable()->index(); $table->timestamp('changed_at')->nullable(); $table->text('notes')->nullable(); $table->json('metadata')->nullable(); $table->timestamps();
            });
        }
    }
    public function down(): void
    {
        foreach (['dispatch_status_logs','travel_time_estimates','dispatch_route_stops','dispatch_routes','customer_locations','service_zones','technician_profile_skill','technician_skills','technician_profiles'] as $table) { Schema::dropIfExists($table); }
    }
};
