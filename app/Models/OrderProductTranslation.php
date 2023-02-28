<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \App\Models\OrderProduct
 */
class OrderProductTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
