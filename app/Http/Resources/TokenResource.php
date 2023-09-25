<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'token' => $this['token'],
            'user_id' => $this['user_id'],
        ];
    }
}
