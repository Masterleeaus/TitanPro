<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('tz_command_workers', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }
    public function down(){Schema::dropIfExists('tz_command_workers');}
};