<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Card */
class CardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'last_numbers' => $this->last_numbers,
            'type' => $this->type,
        ];
    }
}
