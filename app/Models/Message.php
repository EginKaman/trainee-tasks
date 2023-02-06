<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var string[]
     */
    protected $casts = [
        'success' => 'bool',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['email', 'name', 'text', 'method', 'success'];
}
