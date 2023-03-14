<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 2,
            'term' => 3,
            'request_amount' => 10000,
            'repayment_amount' => 30000,
            'request_at' => Carbon::create(2023, 02, 07),
            'approved_by' => 0,
            'details' => fake()->text(),
            'status' => 'pending',
        ];
    }
}
