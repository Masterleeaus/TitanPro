<?php

namespace Modules\JobManager\Console;

use Illuminate\Console\Command;


namespace Modules\JobManager\Console;

<?php

namespace ModulesJobManagerConsole;


namespace Modules\JobManager\Console;

use Illuminate\Console\Command;
class JobManagerExportCsvCommand extends Command
{
    protected $signature = 'jobmanager:export-csv {--path=jobmanager_export.csv}';
    protected $description = 'Export Job Manager to a CSV (storage/app by default).';

    public function handle(): int
    {
        $path = $this->option('path') ?: 'jobmanager_export.csv';
        $rows = [];
        $headers = ['id','title','status','priority','customer_id','scheduled_at','created_at','updated_at'];
        $rows[] = implode(',', $headers);

        WorkOrder::query()->orderBy('id')->chunk(500, function($chunk) use (&$rows) {
            foreach ($chunk as $wo) {
                $rows[] = implode(',', [
                    $wo->id,
                    $this->csv($wo->title),
                    $wo->status,
                    $wo->priority,
                    $wo->customer_id,
                    $wo->scheduled_at,
                    $wo->created_at,
                    $wo->updated_at,
                ]);
            }
        });

        Storage::put($path, implode("\n", $rows));
        $this->info("Exported " . (count($rows)-1) . " work orders to storage/app/{$path}");
        return 0;
    }

    protected function csv($val): string
    {
        $s = (string) $val;
        $s = str_replace('"', '""', $s);
        if (preg_match('/[",\n]/', $s)) {
            $s = '"'.$s.'"';
        }
        return $s;
    }
}
