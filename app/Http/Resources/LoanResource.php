<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'LoanResource',
    properties: [
        new OAT\Property(property: 'id', type: 'integer', example: 1),
        new OAT\Property(property: 'term', type: 'integer', example: 3),
        new OAT\Property(property: 'request_amount', type: 'integer', example: 50000),
        new OAT\Property(property: 'request_at', type: 'datetime', example: '2022-08-27T16:14:46.000000Z'),
        new OAT\Property(property: 'status', type: 'string', example: 'pending|approved|paid'),
        new OAT\Property(property: 'details', type: 'string', example: 'abcdefgh'),
        new OAT\Property(property: 'approved_by', type: 'integer', example: 3),
        new OAT\Property(property: 'updated_at', type: 'datetime', example: '2022-08-27T16:14:46.000000Z'),
        new OAT\Property(property: 'created_at', type: 'datetime', example: '2022-08-27T16:14:46.000000Z'),
    ]
)]
class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'term' => $this->term,
            'request_amount' => $this->request_amount,
            'request_at' => $this->request_at,
            'status' => $this->status,
            'details' => $this->details,
            'approved_by' => $this->approved_by,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
