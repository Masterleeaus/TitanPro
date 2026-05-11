<?php
namespace App\Extensions\TitanPulse\System\Console\Commands;
use App\Extensions\TitanPulse\System\Models\AutomationRuleSet;
use App\Extensions\TitanPulse\System\Services\Packs\BuiltInPackCatalog;
use Illuminate\Console\Command;
class PulsePacksCommand extends Command {
    protected $signature = 'titan:pulse-packs {action=list : list|enable|disable|seed} {pack?} {--team_id=}';
    protected $description = 'Inspect and toggle Titan Pulse rule packs per tenant.';

    public function handle(BuiltInPackCatalog $catalog): int {
        $action=(string)$this->argument('action');
        $pack=$this->argument('pack');
        $teamId=$this->option('team_id') ? (int)$this->option('team_id') : null;
        return match($action) {
            'seed' => $this->seed($catalog,$teamId),
            'enable' => $this->toggle((string)$pack,true,$teamId),
            'disable' => $this->toggle((string)$pack,false,$teamId),
            default => $this->showList($catalog,$teamId),
        };
    }

    private function showList(BuiltInPackCatalog $catalog, ?int $teamId): int {
        $rows=[];
        foreach ($catalog->all() as $name => $meta) {
            $enabled = AutomationRuleSet::query()->where('name',$name)
                ->where(function($q) use ($teamId){
                    if ($teamId) { $q->where('team_id',$teamId)->orWhereNull('team_id'); }
                    else { $q->whereNull('team_id'); }
                })->where('enabled',1)->exists();
            $rows[] = [$name, $enabled ? 'yes' : 'no', $teamId ?: 'global', $meta['description']];
        }
        $this->table(['Pack','Enabled','Scope','Description'],$rows);
        return self::SUCCESS;
    }

    private function seed(BuiltInPackCatalog $catalog, ?int $teamId): int {
        foreach ($catalog->all() as $name => $meta) {
            AutomationRuleSet::query()->updateOrCreate(
                ['team_id'=>$teamId,'name'=>$name],
                ['company_id'=>$teamId,'user_id'=>null,'description'=>$meta['description'],'enabled'=>1,'version'=>'1.0']
            );
        }
        $this->info('Pulse packs seeded'.($teamId ? ' for team '.$teamId : ' globally').'.');
        return self::SUCCESS;
    }

    private function toggle(string $pack, bool $enabled, ?int $teamId): int {
        if ($pack === '') { $this->error('Pack name required.'); return self::FAILURE; }
        AutomationRuleSet::query()->updateOrCreate(
            ['team_id'=>$teamId,'name'=>$pack],
            ['company_id'=>$teamId,'user_id'=>null,'enabled'=>$enabled]
        );
        $this->info(sprintf('Pack %s %s for %s.', $pack, $enabled ? 'enabled' : 'disabled', $teamId ? 'team '.$teamId : 'global'));
        return self::SUCCESS;
    }
}
