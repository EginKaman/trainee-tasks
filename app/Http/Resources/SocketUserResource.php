<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\User */
class SocketUserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->when($this->photo_small !== null, url(Storage::url($this->photo_small))),
            'online' => $this->online,
            'time' => now(),
        ];
    }
}
