<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TournamentUser extends Pivot
{
    public $timestamps = true;

    protected $fillable = ['score'];
}
