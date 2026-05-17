<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('modules')) {
            $exists = DB::table('modules')
                ->where('module_name', 'Titan Docs')
                ->exists();

            if (! $exists) {
                DB::table('modules')->insert([
                    'module_name'   => 'Titan Docs',
                    'description'   => 'Titan Docs – SWMS & office doc generator powered by Titan Core.',
                    'is_superadmin' => 1,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        if (Schema::hasTable('module_settings') && Schema::hasTable('companies')) {
            $companies = DB::table('companies')->select('id')->get();

            foreach ($companies as $company) {
                foreach (['admin', 'employee'] as $type) {
                    $exists = DB::table('module_settings')
                        ->where('company_id', $company->id)
                        ->where('module_name', 'Titan Docs')
                        ->where('type', $type)
                        ->exists();

                    if (! $exists) {
                        DB::table('module_settings')->insert([
                            'company_id'  => $company->id,
                            'module_name' => 'Titan Docs',
                            'status'      => 'active',
                            'type'        => $type,
                            'is_allowed'  => 1,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('module_settings')) {
            DB::table('module_settings')
                ->where('module_name', 'Titan Docs')
                ->delete();
        }

        if (Schema::hasTable('modules')) {
            DB::table('modules')
                ->where('module_name', 'Titan Docs')
                ->delete();
        }
    }
};
