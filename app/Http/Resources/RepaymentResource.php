<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'RepaymentResource',
    properties: [
        new OAT\Property(property: 'id', type: 'integer', example: 1),
        new OAT\Property(property: 'user_id', type: 'integer', example: 3),
        new OAT\Property(property: 'loan_id', type: 'integer', example: 3),
        new OAT\Property(property: 'due_date', type: 'datetime', example: '2022-08-27T16:14:46.000000Z'),
        new OAT\Property(property: 'due_amount', type: 'integer', example: 3000),
        new OAT\Property(property: 'paid_amount', type: 'integer', example: 3000),
        new OAT\Property(property: 'status', type: 'string', example: 'pending|paid'),
        new OAT\Property(property: 'paid_at', type: 'datetime', example: '2022-08-27T16:14:46.000000Z'),
    ]
)]
class RepaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'loan_id' => $this->loan_id,
            'due_date' => $this->due_date,
            'due_amount' => $this->due_amount,
            'paid_amount' => $this->paid_amount,
            'paid_at' => $this->paid_at,
            'status' => $this->status,
        ];
    }
}
