<?php

namespace App\Listeners;

use App\Events\RepaymentPaid;
use App\Services\LoanService;

class UpdateLoanStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private LoanService $loandService)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RepaymentPaid  $event
     * @return void
     */
    public function handle(RepaymentPaid $event)
    {
        $this->loandService->updatePaidAfterMakePayment($event->repayment->loan_id);
    }
}
