<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wo_service_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('wo_id')->default(0)->index();
            $table->unsignedBigInteger('work_order_id')->nullable()->index();
            $table->unsignedBigInteger('service_part_id')->nullable()->index();
            $table->unsignedBigInteger('service_task_id')->nullable()->index();
            $table->string('service_task')->nullable();
            $table->string('duration')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            $table->decimal('qty', 12, 2)->default(1);
            $table->decimal('rate', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('wo_service_tasks'); }
};
