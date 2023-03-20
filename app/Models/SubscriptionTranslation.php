<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $subscription_id
 * @property string $locale
 * @property string $title
 * @property string $description
 */
class SubscriptionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
