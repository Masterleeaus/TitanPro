<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

it('exports theme tokens as css json and tailwind config files', function () {
    $directory = storage_path('framework/testing/titan-token-export-'.Str::random(8));

    Artisan::call('titan:tokens:export', ['--path' => $directory]);

    expect(file_exists($directory.'/theme.css'))->toBeTrue()
        ->and(file_exists($directory.'/style-dictionary.json'))->toBeTrue()
        ->and(file_exists($directory.'/tailwind.tokens.js'))->toBeTrue()
        ->and(file_get_contents($directory.'/theme.css'))->toContain('--btn-primary-bg: var(--color-primary);')
        ->and(file_get_contents($directory.'/style-dictionary.json'))->toContain('semantic')
        ->and(file_get_contents($directory.'/tailwind.tokens.js'))->toContain('var(--color-primary)');
});
