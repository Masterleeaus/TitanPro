<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('deals')) { Schema::table('deals', function (Blueprint $table) { if (! Schema::hasColumn('deals', 'crmcore_customer_id')) { $table->foreignId('crmcore_customer_id')->nullable()->after('contact_id')->constrained('customers')->nullOnDelete(); } }); }
        if (Schema::hasTable('leads')) { Schema::table('leads', function (Blueprint $table) { if (! Schema::hasColumn('leads', 'crmcore_customer_id')) { $table->foreignId('crmcore_customer_id')->nullable()->after('company_name')->constrained('customers')->nullOnDelete(); } }); }
    }
    public function down(): void
    {
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'crmcore_customer_id')) { Schema::table('leads', fn (Blueprint $table) => $table->dropConstrainedForeignId('crmcore_customer_id')); }
        if (Schema::hasTable('deals') && Schema::hasColumn('deals', 'crmcore_customer_id')) { Schema::table('deals', fn (Blueprint $table) => $table->dropConstrainedForeignId('crmcore_customer_id')); }
    }
};
