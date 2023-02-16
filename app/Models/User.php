<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *
 *     @OA\Property(property="name", type="string", format="email", description="User name"),
 *     @OA\Property(property="email", type="string", format="email", description="User email"),
 *     @OA\Property(property="phone", type="string", format="url", description="User phone number"),
 *     @OA\Property(property="photo", type="string", format="url", description="User photo"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Soft delete timestamp", readOnly="true"),
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
