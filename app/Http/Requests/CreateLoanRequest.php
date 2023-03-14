<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'CreateLoanRequest',
    required: ['amount', 'term'],
    properties: [
        new OAT\Property(
            property: 'amount',
            type: 'numeric',
            example: '50000'
        ),
        new OAT\Property(
            property: 'term',
            type: 'numeric',
            example: '3'
        ),
    ]
)]
class CreateLoanRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'term' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ];
    }
}
