#!/bin/bash
set -e

cd /home/saassmar/domains/tradiesm.art/public_html

if ! grep -q "TitanLivewireFallbackServiceProvider" bootstrap/providers.php; then
php -r '
$file = "bootstrap/providers.php";
$content = file_get_contents($file);
$content = preg_replace(
    "/\n\];\s*$/",
    "\n    App\\\\Providers\\\\TitanLivewireFallbackServiceProvider::class,\n];\n",
    $content
);
file_put_contents($file, $content);
';
fi

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan livewire:discover || true

echo "Fallback widget installed.";
