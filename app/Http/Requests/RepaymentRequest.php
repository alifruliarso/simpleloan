<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'RepaymentRequest',
    required: ['amount', 'loan_id'],
    properties: [
        new OAT\Property(
            property: 'amount',
            type: 'numeric',
            example: '50000'
        ),
        new OAT\Property(
            property: 'loan_id',
            type: 'numeric',
            example: '3'
        ),
    ]
)]
class RepaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'loan_id' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ];
    }
}
