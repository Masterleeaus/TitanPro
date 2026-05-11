<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('tz_command_assignments', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(){Schema::dropIfExists('tz_command_assignments');}
};