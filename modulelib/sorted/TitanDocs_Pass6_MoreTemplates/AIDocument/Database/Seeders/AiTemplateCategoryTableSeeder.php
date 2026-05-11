<?php

namespace Modules\AIDocument\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AIDocument\Entities\AiTemplateCategory;
class AiTemplateCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $templates = [
            ['id' => 1, 'name' => 'content','status'=>true],
            ['id' => 2, 'name' => 'blog','status'=>true],
            ['id' => 3, 'name' => 'Website','status'=>true],
            ['id' => 4, 'name' => 'Social Media','status'=>true],
            ['id' => 5, 'name' => 'Video','status'=>true],
            ['id' => 6, 'name' => 'email','status'=>true],
            ['id' => 7, 'name' => 'other','status'=>true],
            
            ['id' => 8, 'name' => 'SWMS - Working at Heights','status'=>true],
            ['id' => 9, 'name' => 'SWMS - Electrical','status'=>true],
            ['id' => 10, 'name' => 'SWMS - Excavation','status'=>true],
            ['id' => 11, 'name' => 'Office Documents','status'=>true],
            ['id' => 12, 'name' => 'SWMS - Roofing','status'=>true],
            ['id' => 13, 'name' => 'SWMS - Concreting','status'=>true],
            ['id' => 14, 'name' => 'SWMS - Plumbing','status'=>true],
            ['id' => 15, 'name' => 'SWMS - Carpentry / Framing','status'=>true],
            ['id' => 16, 'name' => 'SWMS - Scaffolding & Edge Protection','status'=>true],
            ['id' => 17, 'name' => 'SWMS - Traffic Management','status'=>true],
            ['id' => 18, 'name' => 'SWMS - Confined Spaces','status'=>true],
            ['id' => 19, 'name' => 'SWMS - Demolition / Strip-out','status'=>true],

        ];

        foreach ($templates as $template) {
            AiTemplateCategory::updateOrCreate(['id' => $template['id']], $template);
        }
        // $this->call("OthersTableSeeder");
    }
}
