<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'status' => $this->resource['status'] ?? 'Success',
            'message' => $this->resource['message'] ?? 'Success',
            'data' => $this->resource['data'] ?? null,
        ];
    }
}
