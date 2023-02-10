<?php

declare(strict_types=1);

namespace App\Models;

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

    public function processingImages(): HasMany
    {
        return $this->hasMany(ProcessingImage::class);
    }
}
