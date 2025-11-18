<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    public function __construct(protected $message, protected $data = null, protected $statusCode = 200)
    {
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'message' => $this->message,
            'data' => $this->data ?? null,
        ];
    }

    public function withResponse(Request $request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
