<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessingImage extends Model
{
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
