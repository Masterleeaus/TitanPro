<?php

namespace Modules\JobManager\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('wo_requests')) {
            Schema::create('wo_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id')->index();
            $table->unsignedBigInteger('requested_by_id')->nullable()->index();
            $table->string('channel')->nullable(); // phone, web, email, app
            $table->text('description')->nullable();
            $table->timestamps();
        });
        }
    }
    public function down(): void {
        Schema::dropIfExists('wo_requests');
    }
};
