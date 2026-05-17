<?php

namespace Modules\JobManager\Database\Seeders;

use Illuminate\Database\Seeder; use Illuminate\Support\Facades\DB; use Illuminate\Support\Facades\Schema;


namespace ModulesJobManagerDatabaseSeeders;

namespace Modules\JobManager\Database\Seeders;
use Illuminate\Database\Seeder; use Illuminate\Support\Facades\DB; use Illuminate\Support\Facades\Schema;
class FieldServiceMenuSeeder extends Seeder {
  public function run(): void {
    $tables=['menus','menu_items','sidebar_menus']; $table=null;
    foreach($tables as $t){ if(Schema::hasTable($t)){ $table=$t; break; } }
    if(!$table){ $this->command?->warn('No menu table found.'); return; }
    $items=[
      ['label'=>'Job Manager','url'=>'/jobmanager','permission'=>'jobmanager.view','icon'=>'fa-clipboard-list','parent'=>null,'order'=>10],
      ['label'=>'Create Job','url'=>'/jobmanager/create','permission'=>'jobmanager.create','icon'=>null,'parent'=>'Job Manager','order'=>11],
      ['label'=>'Assignments','url'=>'/contractors/assignments','permission'=>'contractors.view_assignments','icon'=>null,'parent'=>null,'order'=>20],
      ['label'=>'Schedule (Day)','url'=>'/contractors/schedule','permission'=>'contractors.view_assignments','icon'=>null,'parent'=>'Assignments','order'=>21],
    ];
    foreach($items as $it){ DB::table($table)->updateOrInsert(['label'=>$it['label']], [
        'url'=>$it['url'],'permission'=>$it['permission'],'icon'=>$it['icon'],
        'parent_id'=>null,'order'=>$it['order'],'created_at'=>now(),'updated_at'=>now()
    ]); }
  }
}
