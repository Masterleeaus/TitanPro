<?php

namespace Modules\JobManager\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('jobmanager_failed_webhooks')) {
            Schema::create('jobmanager_failed_webhooks', function (Blueprint $table) {
            $table->id();
            $table->longText('payload')->nullable();
            $table->string('error', 512)->nullable();
            $table->timestamps();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jobmanager_failed_webhooks');
    }
};
