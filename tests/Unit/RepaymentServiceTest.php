<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\RepaymentRepository;
use App\Services\RepaymentService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class RepaymentServiceTest extends TestCase
{
    use WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user, [], 'user');
    }

    public function testGenerateRepayments()
    {
        $loanId = 1;
        $userId = 2;
        $paymentTerm = 3;

        $mockRepo = Mockery::mock(RepaymentRepository::class);
        $mockRepo->expects('bulkInsert')->withArgs(function ($schedules) use ($userId, $loanId, $paymentTerm) {
            static::assertCount($paymentTerm, $schedules);
            $totalRepayment = 0;

            for ($i = 0; $i < count($schedules); $i++) {
                static::assertTrue($schedules[$i]['status'] === 'pending');
                static::assertTrue($schedules[$i]['user_id'] === $userId);
                static::assertTrue($schedules[$i]['loan_id'] === $loanId);
                $totalRepayment += $schedules[$i]['due_amount'];

                $dueDateExists = false;
                $actualDueDate = new Carbon($schedules[$i]['due_date']);
                $loanDate = Carbon::create(2023, 02, 07);
                for ($j = 1; $j <= $paymentTerm; $j++) {
                    $expectedDueDate = clone $loanDate->addWeeks(1);
                    if ($actualDueDate->equalTo($expectedDueDate)) {
                        $dueDateExists = true;
                    }
                }
                static::assertTrue($dueDateExists);
            }

            static::assertEqualsWithDelta(10000, $totalRepayment, 0.00000001);

            return true;
        });

        app()->instance(RepaymentRepository::class, $mockRepo);

        $loan = Loan::factory()->create([
            'id' => $loanId,
            'user_id' => $userId,
            'term' => 3,
            'request_amount' => 10000,
            'repayment_amount' => 10000,
            'request_at' => Carbon::create(2023, 02, 07),
            'approved_by' => 0,
            'details' => fake()->text(),
            'status' => 'pending',
        ]);
        (new RepaymentService($mockRepo))->generateRepayments($loan);
    }
}
