<?php
namespace App\Extensions\TitanPulse\Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TitanPulsePackSeeder extends Seeder {
    public function run(): void {
        if (!DB::getSchemaBuilder()->hasTable('tz_automation_rule_sets')) return;
        $packs=[['name'=>'CleaningOpsPack','description'=>'Operational work rules for scheduling, dispatch, proof and invoicing.'],['name'=>'QualityRetentionPack','description'=>'Quality control, retention and upsell rules.'],['name'=>'MarketingPack','description'=>'Lead, inbox, content and campaign automations.'],['name'=>'FinancePack','description'=>'Invoice, overdue, payment and loyalty automations.'],['name'=>'TrustPack','description'=>'Complaints, reviews, supervisor and recovery workflows.']];
        foreach($packs as $pack){ DB::table('tz_automation_rule_sets')->updateOrInsert(['team_id'=>null,'name'=>$pack['name']],['company_id'=>null,'user_id'=>null,'description'=>$pack['description'],'enabled'=>1,'updated_at'=>now(),'created_at'=>now()]); }
    }
}
