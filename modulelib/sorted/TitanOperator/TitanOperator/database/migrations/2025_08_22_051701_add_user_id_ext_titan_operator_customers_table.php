<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ext_titan_operator_customers', function (Blueprint $table) {
            $table->bigInteger('user_id')->after('id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_titan_operator_customers');
    }
};
