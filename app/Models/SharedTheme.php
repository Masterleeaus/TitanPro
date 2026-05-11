<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * A theme that has been shared via a public share link.
 *
 * @property int    $id
 * @property string $token   Short random token embedded in the share URL.
 * @property string $name
 * @property string|null $author
 * @property array  $tokens  Theme token values (primary_color, secondary_color, etc.)
 * @property int    $views
 */
class SharedTheme extends Model
{
    protected $fillable = [
        'token',
        'name',
        'author',
        'tokens',
        'views',
    ];

    protected $casts = [
        'tokens' => 'array',
        'views'  => 'integer',
    ];

    /** Create a new shared theme and return the generated token. */
    public static function createFromTokens(string $name, ?string $author, array $tokens): self
    {
        return static::create([
            'token'  => Str::random(24),
            'name'   => $name,
            'author' => $author,
            'tokens' => $tokens,
        ]);
    }
}
