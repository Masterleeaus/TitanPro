<?php

namespace App\Console\Commands;

use App\Support\ThemeComponentRegistry;
use Illuminate\Console\Command;

class ThemeComponentsCommand extends Command
{
    protected $signature = 'theme:components';
    protected $description = 'List registered design-system components.';

    public function handle(): int
    {
        foreach (ThemeComponentRegistry::components() as $key => $component) {
            $this->line($key . ': ' . $component['label']);
        }

        return self::SUCCESS;
    }
}
