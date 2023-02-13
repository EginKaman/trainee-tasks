<?php

declare(strict_types=1);

namespace App\Models;

use App\Facades\FileHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessingImage extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'path',
        'mimetype',
        'original_size',
        'original_height',
        'original_width',
        'kraked_size',
        'kraked_height',
        'kraked_width',
        'save_bytes',
        'status',
    ];

    protected $casts = [];

    public function sizeForHumans(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => FileHelper::sizeForHumans(
                $attributes['kraked_size'] ?? $attributes['original_size']
            )
        );
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
