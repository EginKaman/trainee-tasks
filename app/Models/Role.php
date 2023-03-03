<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 */
class Role extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['title'];
}
