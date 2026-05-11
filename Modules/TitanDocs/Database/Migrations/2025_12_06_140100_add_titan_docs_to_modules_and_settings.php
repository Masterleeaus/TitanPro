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
            $companyIds = DB::table('companies')->pluck('id')->all();

            if ($companyIds !== []) {
                $types = ['admin', 'employee'];
                $existingRows = DB::table('module_settings')
                    ->where('module_name', 'Titan Docs')
                    ->whereIn('company_id', $companyIds)
                    ->whereIn('type', $types)
                    ->get(['company_id', 'type']);

                $existingKeys = [];
                foreach ($existingRows as $existingRow) {
                    $existingKeys[$existingRow->company_id.'|'.$existingRow->type] = true;
                }

                $rowsToInsert = [];
                foreach ($companyIds as $companyId) {
                    foreach ($types as $type) {
                        $key = $companyId.'|'.$type;

                        if (isset($existingKeys[$key])) {
                            continue;
                        }

                        $rowsToInsert[] = [
                            'company_id'  => $companyId,
                            'module_name' => 'Titan Docs',
                            'status'      => 'active',
                            'type'        => $type,
                            'is_allowed'  => 1,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];
                    }
                }

                foreach (array_chunk($rowsToInsert, 500) as $chunk) {
                    DB::table('module_settings')->insert($chunk);
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
