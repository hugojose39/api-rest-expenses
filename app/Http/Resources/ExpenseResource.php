<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'description' => $this->description,
            'date' => $this->date,
            'user_id' => $this->user_id,
            'value' => $this->value,
            'updated_at' => $this->updated_at,
        ];
    }
}
