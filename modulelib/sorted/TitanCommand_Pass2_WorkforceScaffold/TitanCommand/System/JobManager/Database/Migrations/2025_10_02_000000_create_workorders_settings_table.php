<?php

namespace App\Extensions\TitanCommand\System\JobManager\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('jobmanager_settings')) {
            Schema::create('jobmanager_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_convert_on_complete')->default(false);
            $table->timestamps();
        });
        }
    }
    public function down(): void {
        Schema::dropIfExists('jobmanager_settings');
    }
};
