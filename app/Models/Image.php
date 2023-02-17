<?php

declare(strict_types=1);

namespace App\Models;

use App\Facades\FileHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = ['filename', 'path', 'hash', 'mimetype', 'height', 'width', 'size', 'converted_at'];

    protected $casts = [
        'height' => 'int',
        'width' => 'int',
        'size' => 'int',
        'converted_at' => 'datetime',
    ];

    public function sizeForHumans(): Attribute
    {
        return Attribute::make(get: fn ($value, $attributes) => FileHelper::sizeForHumans($attributes['size']));
    }

    public function dimension(): Attribute
    {
        return Attribute::make(get: fn ($value, $attributes) => "{$attributes['width']}x{$attributes['height']}px");
    }

    public function processingImages(): HasMany
    {
        return $this->hasMany(ProcessingImage::class);
    }
}
