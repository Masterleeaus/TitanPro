<?php

namespace App\Filament\Admin\Pages;

use App\Support\Cms\TomatoBlogBridge;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;

class SiteCms extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|\UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Site CMS';

    protected static ?string $title = 'Site CMS';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'site-cms';

    protected string $view = 'filament.admin.pages.site-cms';

    public function getCmsCardsProperty(): array
    {
        return [
            [
                'label' => 'Site Pages',
                'description' => 'Manage landing pages, product pages, SEO metadata, and editable site sections.',
                'url' => url('/admin/cms-pages'),
                'status' => class_exists(\App\Filament\Resources\CmsPageResource::class) ? 'Ready' : 'Missing',
            ],
            [
                'label' => 'Blog Posts',
                'description' => 'Tomato CMS powers blog posts and articles. This keeps blog publishing inside the site CMS workflow.',
                'url' => TomatoBlogBridge::adminUrl('admin'),
                'status' => TomatoBlogBridge::isAvailable() ? 'Ready' : 'Run CMS migrations',
            ],
            [
                'label' => 'Media Library',
                'description' => 'Curator manages reusable media for pages, blog posts, and brand assets.',
                'url' => url('/admin/curator/media'),
                'status' => 'Linked',
            ],
            [
                'label' => 'Menus & Navigation',
                'description' => 'Menu Builder controls theme navigation and site-level menu items.',
                'url' => url('/admin/menus'),
                'status' => 'Linked',
            ],
            [
                'label' => 'Public Blog',
                'description' => 'Preview the public blog index backed by Tomato CMS content.',
                'url' => TomatoBlogBridge::publicUrl(),
                'status' => 'Public',
            ],
        ];
    }

    public function getBlogStatsProperty(): array
    {
        return TomatoBlogBridge::stats();
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
