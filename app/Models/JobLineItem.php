<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'item_id',
        'name',
        'description',
        'unit_price',
        'quantity',
        'total',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item): void {
            $item->total = round((float) $item->unit_price * (float) $item->quantity, 2);
        });
    }

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity'   => 'decimal:3',
            'total'      => 'decimal:2',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
