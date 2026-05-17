<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TitanInPageUiSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('titanInPageUiSettingsAssets', function () {
            return <<<'PHP'
<?php
    $css = public_path('titan-ui-settings/titan-ui-settings.css');
    $js = public_path('titan-ui-settings/titan-ui-settings.js');
?>
@if (file_exists($css))
    <link rel="stylesheet" href="{{ asset('titan-ui-settings/titan-ui-settings.css') }}?v={{ filemtime($css) }}">
@endif
@if (file_exists($js))
    <script defer src="{{ asset('titan-ui-settings/titan-ui-settings.js') }}?v={{ filemtime($js) }}"></script>
@endif
PHP;
        });
    }
}
