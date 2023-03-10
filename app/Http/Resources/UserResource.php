<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo_big' => $this->when($this->photo_big !== null, url(Storage::url($this->photo_big))),
            'photo_small' => $this->when($this->photo_small !== null, url(Storage::url($this->photo_small))),
            'role' => $this->whenLoaded('role', $this->role?->title),
        ];
    }
}
