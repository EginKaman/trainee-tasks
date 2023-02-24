<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo_big' => url(Storage::url($this->photo_big)),
            'photo_small' => url(Storage::url($this->photo_small)),
            'role' => $this->whenLoaded('role', $this->role?->title),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
