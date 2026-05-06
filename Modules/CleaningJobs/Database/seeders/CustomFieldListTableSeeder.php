<?php

namespace Workdo\JobBoard\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use CustomFieldsModuleList;

class CustomFieldListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $sub_module = [
            'Projects',
            'Tasks',
            'Bugs',
        ];
        if(module_is_active('CustomField'))
        {
            foreach($sub_module as $sm){
                $check = \Workdo\CustomField\Entities\CustomFieldsModuleList::where('module','JobBoard')->where('sub_module',$sm)->first();
                if(!$check){
                    $new = new \Workdo\CustomField\Entities\CustomFieldsModuleList();
                    $new->module = 'JobBoard';
                    $new->sub_module = $sm;
                    $new->save();
                }
            }
        }
    }
}
