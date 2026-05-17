#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

# Register service provider safely.
if [ -f bootstrap/providers.php ] && ! grep -q "TitanInPageUiSettingsServiceProvider" bootstrap/providers.php; then
php -r '
$file="bootstrap/providers.php";
$s=file_get_contents($file);
$s=preg_replace("/\n\];\s*$/", "\n    App\\\\Providers\\\\TitanInPageUiSettingsServiceProvider::class,\n];\n", $s);
file_put_contents($file,$s);
'
fi

# Register middleware alias/global by patching bootstrap/app.php where possible.
if [ -f bootstrap/app.php ] && ! grep -q "InjectTitanInPageUiSettings" bootstrap/app.php; then
php -r '
$file="bootstrap/app.php";
$s=file_get_contents($file);
$needle="->withMiddleware(function (Middleware $middleware): void {";
if (str_contains($s, $needle)) {
    $s=str_replace($needle, $needle."\n        $middleware->append(\\App\\Http\\Middleware\\InjectTitanInPageUiSettings::class);", $s);
}
file_put_contents($file,$s);
'
fi

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true

echo "In-page UI settings drawer installed."
echo "Open any dashboard and click the floating gear button bottom-right."
