<?php

namespace App\Support\Cms;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use stdClass;

class TomatoBlogBridge
{
    public static function table(): ?string
    {
        foreach (['posts', 'blog_posts', 'cms_posts', 'tomato_posts'] as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    public static function isAvailable(): bool
    {
        return self::table() !== null;
    }

    public static function adminUrl(string $panel = 'admin'): string
    {
        return url('/'.trim($panel, '/').'/posts');
    }

    public static function publicUrl(): string
    {
        return url('/blog');
    }

    public static function posts(int $perPage = 9): LengthAwarePaginator
    {
        $table = self::table();

        if (! $table) {
            return new LengthAwarePaginator([], 0, $perPage);
        }

        $query = DB::table($table);

        if (Schema::hasColumn($table, 'type')) {
            $query->where(function ($query): void {
                $query->whereNull('type')->orWhereIn('type', ['post', 'blog', 'article']);
            });
        }

        if (Schema::hasColumn($table, 'status')) {
            $query->whereIn('status', ['published', 'publish', 'active', '1', 1, true]);
        }

        if (Schema::hasColumn($table, 'published_at')) {
            $query->where(function ($query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            })->orderByDesc('published_at');
        } elseif (Schema::hasColumn($table, 'created_at')) {
            $query->orderByDesc('created_at');
        }

        return $query->paginate($perPage)->through(fn ($post) => self::normalise($post));
    }

    public static function findBySlug(string $slug): ?stdClass
    {
        $table = self::table();

        if (! $table) {
            return null;
        }

        $query = DB::table($table);

        if (Schema::hasColumn($table, 'slug')) {
            $query->where('slug', $slug);
        } else {
            $query->where('id', $slug);
        }

        if (Schema::hasColumn($table, 'status')) {
            $query->whereIn('status', ['published', 'publish', 'active', '1', 1, true]);
        }

        $post = $query->first();

        return $post ? self::normalise($post) : null;
    }

    public static function stats(): array
    {
        $table = self::table();

        if (! $table) {
            return [
                'available' => false,
                'table' => null,
                'total' => 0,
                'published' => 0,
                'draft' => 0,
            ];
        }

        $base = DB::table($table);
        $total = (clone $base)->count();
        $published = Schema::hasColumn($table, 'status')
            ? (clone $base)->whereIn('status', ['published', 'publish', 'active', '1', 1, true])->count()
            : $total;
        $draft = max(0, $total - $published);

        return compact('table', 'total', 'published', 'draft') + ['available' => true];
    }

    protected static function normalise(object $post): stdClass
    {
        $post->cms_title = self::firstValue($post, ['title', 'name', 'headline'], 'Untitled post');
        $post->cms_slug = self::firstValue($post, ['slug', 'id'], Str::slug($post->cms_title));
        $post->cms_excerpt = self::firstValue($post, ['excerpt', 'summary', 'description', 'short_description'], '');
        $post->cms_body = self::firstValue($post, ['body', 'content', 'description', 'article', 'text'], '');
        $post->cms_published_at = self::firstValue($post, ['published_at', 'created_at', 'updated_at'], null);
        $post->cms_image = self::firstValue($post, ['image', 'cover', 'cover_image', 'featured_image', 'thumbnail'], null);

        return $post;
    }

    protected static function firstValue(object $object, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            if (property_exists($object, $key) && filled($object->{$key})) {
                return $object->{$key};
            }
        }

        return $default;
    }
}
