<?php

namespace App\Services;

use App\Http\Requests\CreateLoanRequest;
use App\Models\Loan;
use App\Repositories\LoanRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LoanService
{
    public function __construct(private LoanRepository $loanRepository, private RepaymentService $repaymentService)
    {
    }

    public function create(CreateLoanRequest $request): Loan
    {
        return DB::transaction(function () use ($request) {
            $loan = $this->loanRepository->create([
                'request_amount' => $request->amount,
                'repayment_amount' => $request->amount,
                'term' => $request->term,
                'request_at' => Carbon::now(),
                'status' => 'pending',
                'user_id' => $request->user()->id,
            ]);
            logger('created ', ['loan' => $loan]);
            $this->repaymentService->generateRepayments($loan);

            return $loan;
        });
    }

    public function list($userId, $page = 1, $limit = 10): mixed
    {
        return $this->loanRepository->filterAndGet($userId, $page, $limit);
    }

    public function approve($loanId)
    {
        $loan = $this->loanRepository->findOrFail($loanId);
        if ($loan['status'] == 'pending') {
            $this->loanRepository->update($loan, ['status' => 'approved']);
        }
    }

    public function updatePaidAfterMakePayment($loanId)
    {
        $loan = $this->loanRepository->findOrFail($loanId);
        if ($loan['status'] == 'approved') {
            $filters = [
                'paid' => false,
                'loanId' => $loanId,
            ];
            $countUnpaid = $this->repaymentService->filterAndCount($loan['user_id'], $filters);
            if ($countUnpaid == 0) {
                $this->loanRepository->update($loan, ['status' => 'paid']);
            }
        }
    }
}
