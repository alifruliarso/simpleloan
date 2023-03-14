<?php

namespace App\Services;

use App\Events\RepaymentPaid;
use App\Http\Requests\RepaymentRequest;
use App\Repositories\RepaymentRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PayRepaymentService
{
    public function __construct(private RepaymentRepository $repaymentRepository)
    {
    }

    public function pay(RepaymentRequest $request, $userId)
    {
        $balancePayment = $request->amount;
        $filters = [
            'paid' => false,
            'loanId' => $request->loan_id,
        ];
        $repaymentList = $this->repaymentRepository->filterAndGet($userId, $filters, 1, 10);
        if (count($repaymentList) == 0) {
            return;
        }

        DB::transaction(function () use ($repaymentList, $balancePayment) {
            foreach ($repaymentList as $repayment) {
                if ($balancePayment <= 0) {
                    return;
                }

                $paidAmount = 0;
                $unpaid = $repayment['due_amount'] - $repayment['paid_amount'];

                if ($unpaid < $balancePayment) {
                    $paidAmount = $repayment['paid_amount'] + $unpaid;
                    $balancePayment -= $unpaid;
                } else {
                    $paidAmount = $balancePayment + $repayment['paid_amount'];
                    $balancePayment = 0;
                }

                $this->repaymentRepository->update($repayment, [
                    'paid_amount' => $paidAmount,
                    'paid_at' => Carbon::now('UTC'),
                    'status' => ($paidAmount == $repayment['due_amount']) ? 'paid' : 'pending',
                ]);
            }
        });
        RepaymentPaid::dispatch($repaymentList[0]);
    }
}
