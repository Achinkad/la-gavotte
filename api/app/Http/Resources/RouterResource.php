<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'identity' => $this->identity,
            'ip_address' => $this->ip_address,
            'mac_address' => $this->mac_address
        ];
    }
}
