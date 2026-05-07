<?php

use App\Support\BrandThemeGenerator;

it('extracts colors and fonts when generating a brand theme', function () {
    $generator = new BrandThemeGenerator();

    $logoPath = tempnam(sys_get_temp_dir(), 'logo').'.svg';
    file_put_contents($logoPath, '<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#ff0000"/><circle cx="50" cy="50" r="30" fill="#0000ff"/></svg>');

    $wallpaperPath = tempnam(sys_get_temp_dir(), 'wall').'.svg';
    file_put_contents($wallpaperPath, '<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#f5f5f5"/></svg>');

    $theme = $generator->generate([
        'logo_absolute_path' => $logoPath,
        'wallpaper_absolute_path' => $wallpaperPath,
        'accent_color' => '#00ff00',
        'font_source_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap',
    ]);

    expect($theme['primary_color'])->toBe('#ff0000')
        ->and($theme['secondary_color'])->toBe('#00ff00')
        ->and($theme['surface_color'])->toBe('#f5f5f5')
        ->and($theme['font_heading'])->toBe('Inter')
        ->and($theme['font_body'])->toBe('Inter');
});

it('sanitizes unsupported font urls', function () {
    $generator = new BrandThemeGenerator();

    expect($generator->sanitizeGoogleFontsUrl('https://fonts.googleapis.com/css2?family=Roboto'))->toBe('https://fonts.googleapis.com/css2?family=Roboto')
        ->and($generator->sanitizeGoogleFontsUrl('https://example.com/font.css'))->toBeNull();
});
