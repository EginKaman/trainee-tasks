<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * @property int $id
 * @property string $socket_id
 * @property bool $online
 * @property string $name
 * @property string $email
 * @property PhoneNumber $phone
 * @property string $photo_small
 * @property string $photo_big
 * @property string $stripe_id
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['socket_id', 'online', 'stripe_id', 'name', 'email', 'phone', 'photo_small', 'photo_big'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['stripe_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone' => E164PhoneNumberCast::class . ':UA',
        'online' => 'bool',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function routeNotificationForTurboSMS(): string
    {
        return $this->phone->formatInternational();
    }

    public function routeNotificationForTwilio(): string
    {
        return $this->phone->formatInternational();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'created_user_id');
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'updated_user_id');
    }

    public function loginTokens(): HasMany
    {
        return $this->hasMany(LoginToken::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(UserProvider::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class)->using(SubscriptionUser::class)->withPivot(
            ['method', 'method_id', 'canceled_at', 'started_at', 'expired_at', 'status']
        )->withTimestamps();
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
