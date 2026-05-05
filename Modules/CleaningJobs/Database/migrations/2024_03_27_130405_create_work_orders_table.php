<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            // Legacy Worksuite fields
            $table->integer('wo_id')->default(0);
            $table->text('wo_detail')->nullable();
            $table->integer('type')->default(0);
            $table->integer('client')->default(0);
            $table->integer('asset')->default(0);
            $table->date('due_date')->nullable();
            $table->string('status')->nullable();
            $table->string('priority')->nullable();
            $table->text('notes')->nullable();
            $table->integer('assign')->default(0);
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('preferred_note')->nullable();
            $table->integer('parent_id')->default(0);
            // Job-management SaaS fields
            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->unsignedBigInteger('technician_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('scheduled_for')->nullable();
            $table->dateTime('due_by')->nullable();
            $table->decimal('total_estimate', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
};
