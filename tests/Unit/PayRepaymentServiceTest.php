<?php

namespace Tests\Unit;

use App\Events\RepaymentPaid;
use App\Http\Requests\RepaymentRequest;
use App\Models\Repayment;
use App\Repositories\RepaymentRepository;
use App\Services\PayRepaymentService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class PayRepaymentServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPayPaidLoan()
    {
        $mockRepo = Mockery::mock(RepaymentRepository::class);
        $mockRepo->shouldReceive('filterAndGet')
            ->once()
            ->andReturn(collect([]));

        app()->instance(RepaymentRepository::class, $mockRepo);

        $reRequest = new RepaymentRequest();
        $reRequest->initialize([
            'amount' => 1000,
            'loan_id' => 1,
        ]);
        (new PayRepaymentService($mockRepo))->pay($reRequest, 2);
    }

    private function buildRepayment()
    {
        $loanId = 1;
        $userId = 2;
        $paymentTerm = 3;
        $loanAmount = 50000;
        $requestDate = Carbon::now('UTC');
        $repaymentSchedule = [];
        $paymentAmount = $loanAmount / $paymentTerm;
        $amountRepayment = round($paymentAmount, 2);
        $totalRepayment = 0;

        for ($i = 1; $i <= $paymentTerm; $i++) {
            if ($i === $paymentTerm) {
                $amountRepayment = $loanAmount - $totalRepayment;
            }
            $repayment = Repayment::factory()->make([
                'user_id' => $userId,
                'loan_id' => $loanId,
                'due_date' => $requestDate->addWeeks($i),
                'due_amount' => $amountRepayment,
                'status' => 'pending',
                'paid_amount' => 0,
                'created_at' => Carbon::now('UTC'),
                'updated_at' => Carbon::now('UTC'),
            ]);
            $repaymentSchedule[] = $repayment;
            $totalRepayment += $amountRepayment;
        }

        return $repaymentSchedule;
    }

    public function testPayOneRepaymentPartial()
    {
        Event::fake();
        $repaymentSchedule = $this->buildRepayment();
        $mockRepo = Mockery::mock(RepaymentRepository::class);
        $mockRepo->shouldReceive('filterAndGet')
            ->once()
            ->andReturn(collect($repaymentSchedule));
        $mockRepo->shouldReceive('update')
            ->once()
            ->andReturn(true);

        app()->instance(RepaymentRepository::class, $mockRepo);

        $reRequest = new RepaymentRequest();
        $reRequest->initialize([
            'amount' => 1000,
            'loan_id' => 1,
        ]);
        (new PayRepaymentService($mockRepo))->pay($reRequest, 2);
        Event::assertDispatched(function (RepaymentPaid $event) {
            return $event->repayment->loan_id === 1;
        });
    }

    public function testPayOneRepaymentFull()
    {
        Event::fake();
        $repaymentSchedule = $this->buildRepayment();
        $mockRepo = Mockery::mock(RepaymentRepository::class);
        $mockRepo->shouldReceive('filterAndGet')
            ->once()
            ->andReturn(collect($repaymentSchedule));
        $mockRepo->shouldReceive('update')
            ->twice()
            ->andReturn(true);

        app()->instance(RepaymentRepository::class, $mockRepo);

        $reRequest = new RepaymentRequest();
        $reRequest->initialize([
            'amount' => 20000,
            'loan_id' => 1,
        ]);
        (new PayRepaymentService($mockRepo))->pay($reRequest, 2);
        Event::assertDispatched(function (RepaymentPaid $event) {
            return $event->repayment->loan_id === 1;
        });
    }
}
