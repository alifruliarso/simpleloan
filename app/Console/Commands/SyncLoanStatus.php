<?php

namespace App\Console\Commands;

use App\Services\LoanService;
use Illuminate\Console\Command;

class SyncLoanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:sync-status {loan_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize status of a loan based on repayment [ loan:sync-status {loan_id} ]';

    public function __construct(private LoanService $loanService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $loanId = $this->argument('loan_id');
        $this->loanService->updatePaidAfterMakePayment($loanId);

        return Command::SUCCESS;
    }
}
