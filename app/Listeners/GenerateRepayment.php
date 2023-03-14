<?php

namespace App\Listeners;

use App\Events\LoanCreated;
use App\Services\RepaymentService;

class GenerateRepayment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private RepaymentService $repaymentService)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LoanCreated  $event
     * @return void
     */
    public function handle(LoanCreated $event)
    {
        $loan = $event->loan;
        logger('handle ', ['loan' => $loan]);
        $this->repaymentService->generateRepayments($loan);
    }
}
