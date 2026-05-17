<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasFieldJobsTable = Schema::hasTable('field_jobs');

        if (! Schema::hasTable('ext_chatbot_portal_booking_requests')) {
            Schema::create('ext_chatbot_portal_booking_requests', function (Blueprint $table) use ($hasFieldJobsTable) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->string('session_id')->nullable()->index();
                $table->dateTime('requested_at')->nullable();
                $table->date('preferred_date')->nullable();
                $table->time('preferred_time')->nullable();
                $table->string('service_type')->nullable();
                $table->text('notes')->nullable();
                $table->string('status')->default('pending')->index();
                $table->unsignedBigInteger('confirmed_job_id')->nullable()->index();
                $table->timestamps();

                if ($hasFieldJobsTable) {
                    $table->foreign('confirmed_job_id')
                        ->references('id')
                        ->on('field_jobs')
                        ->nullOnDelete();
                }
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_recurring_services')) {
            Schema::create('ext_chatbot_portal_recurring_services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('job_id')->nullable()->index();
                $table->string('frequency')->default('monthly');
                $table->boolean('is_paused')->default(false);
                $table->date('pause_until')->nullable();
                $table->boolean('skip_next')->default(false);
                $table->json('permanent_extras')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_site_profiles')) {
            Schema::create('ext_chatbot_portal_site_profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('property_id')->nullable()->index();
                $table->text('alarm_code')->nullable();
                $table->text('alarm_instructions')->nullable();
                $table->json('pets')->nullable();
                $table->string('parking')->nullable();
                $table->string('access_method')->default('key');
                $table->json('priority_rooms')->nullable();
                $table->text('special_instructions')->nullable();
                $table->dateTime('last_updated_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_document_links')) {
            Schema::create('ext_chatbot_portal_document_links', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('document_type')->default('other');
                $table->string('title');
                $table->string('file_path')->nullable();
                $table->string('external_url')->nullable();
                $table->dateTime('expires_at')->nullable();
                $table->boolean('is_signed')->default(false);
                $table->dateTime('signed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_feedback')) {
            Schema::create('ext_chatbot_portal_feedback', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('job_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('session_id')->nullable()->index();
                $table->unsignedTinyInteger('rating')->nullable();
                $table->text('comment')->nullable();
                $table->boolean('reclean_requested')->default(false);
                $table->text('reclean_reason')->nullable();
                $table->dateTime('reclean_scheduled_at')->nullable();
                $table->string('status')->default('new')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_notifications')) {
            Schema::create('ext_chatbot_portal_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('event_type');
                $table->string('title');
                $table->text('body')->nullable();
                $table->string('action_url')->nullable();
                $table->boolean('is_read')->default(false);
                $table->dateTime('read_at')->nullable();
                $table->dateTime('sent_at')->nullable();
                $table->string('channel')->default('in_app');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_actions')) {
            Schema::create('ext_chatbot_portal_actions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('session_id')->nullable()->index();
                $table->string('action_type');
                $table->json('payload')->nullable();
                $table->string('status')->default('queued')->index();
                $table->json('result')->nullable();
                $table->dateTime('processed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_page_visits')) {
            Schema::create('ext_chatbot_page_visits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('session_id')->nullable()->index();
                $table->unsignedBigInteger('visitor_id')->nullable()->index();
                $table->text('page_url');
                $table->string('page_title')->nullable();
                $table->text('referrer')->nullable();
                $table->unsignedInteger('duration_seconds')->nullable();
                $table->dateTime('visited_at')->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_chatbot_portal_automation_logs')) {
            Schema::create('ext_chatbot_portal_automation_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('trigger_event');
                $table->json('trigger_payload')->nullable();
                $table->string('action_taken');
                $table->json('action_payload')->nullable();
                $table->string('outcome')->nullable();
                $table->dateTime('processed_at')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_chatbot_portal_automation_logs');
        Schema::dropIfExists('ext_chatbot_page_visits');
        Schema::dropIfExists('ext_chatbot_portal_actions');
        Schema::dropIfExists('ext_chatbot_portal_notifications');
        Schema::dropIfExists('ext_chatbot_portal_feedback');
        Schema::dropIfExists('ext_chatbot_portal_document_links');
        Schema::dropIfExists('ext_chatbot_portal_site_profiles');
        Schema::dropIfExists('ext_chatbot_portal_recurring_services');
        Schema::dropIfExists('ext_chatbot_portal_booking_requests');
    }
};
