<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'user'    => new UserResource($this->user),
            'admin'   => new UserResource($this->admin),
            'receipt' => new ReceiptResource($this->receipt)
        ];
    }
}
