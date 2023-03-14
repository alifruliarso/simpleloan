<?php

namespace App\Services;

use App\Models\Loan;
use App\Repositories\RepaymentRepository;
use Illuminate\Support\Carbon;

class RepaymentService
{
    public function __construct(private RepaymentRepository $repaymentRepository)
    {
    }

    public function generateRepayments(Loan $loan)
    {
        logger('generate repayment', ['term' => $loan->term, 'request_amount' => $loan->request_amount]);
        $repaymentSchedule = [];
        $loanAmount = $loan->request_amount;
        $loanTerm = $loan->term;
        $paymentAmount = $loanAmount / $loanTerm;
        $amountRepayment = round($paymentAmount, 2);
        $totalRepayment = 0;

        for ($i = 1; $i <= $loanTerm; $i++) {
            if ($i === $loanTerm) {
                $amountRepayment = $loanAmount - $totalRepayment;
            }

            $repaymentSchedule[] = [
                'user_id' => $loan->user_id,
                'loan_id' => $loan->id,
                'due_date' => $loan->request_at->addWeeks($i),
                'due_amount' => $amountRepayment,
                'status' => 'pending',
                'paid_amount' => 0,
                'created_at' => Carbon::now('UTC'),
                'updated_at' => Carbon::now('UTC'),
            ];
            $totalRepayment += $amountRepayment;
        }

        $this->repaymentRepository->bulkInsert($repaymentSchedule);
    }

    public function list($userId, $filters, $page = 1, $limit = 10): mixed
    {
        return $this->repaymentRepository->filterAndGet($userId, $filters, $page, $limit);
    }

    public function getByLoanId($loanId)
    {
        return $this->repaymentRepository->get(['loan_id' => $loanId], takeOne: true);
    }

    public function filterAndCount($userId, $filters)
    {
        return (int) $this->repaymentRepository->filterAndCount($userId, $filters);
    }
}
