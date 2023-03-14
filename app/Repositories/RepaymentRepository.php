<?php

namespace App\Repositories;

use App\Models\Repayment;

/**
 * Summary of LoanRepository
 */
class RepaymentRepository extends BaseRepository
{
    public function __construct(Repayment $repayment)
    {
        $this->model = $repayment;
    }

    public function filterAndGet($userId, $filters, $page = 1, $limit = 10): mixed
    {
        if ($userId === 0) {
            return new \Illuminate\Database\Eloquent\Collection();
        }
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('due_date');

        if (isset($filters['loanId'])) {
            $query = $query->where('loan_id', $filters['loanId']);
        }
        if (isset($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        if (isset($filters['paid'])) {
            if ($filters['paid'] === false) {
                $query = $query->notPaid();
            }
        }

        return $query->take($limit)->skip($limit * ($page - 1))->get();
    }

    public function filterAndCount($userId, $filters)
    {
        if ($userId === 0) {
            return new \Illuminate\Database\Eloquent\Collection();
        }
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('due_date');

        if (isset($filters['loanId'])) {
            $query = $query->where('loan_id', $filters['loanId']);
        }
        if (isset($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        if (isset($filters['paid'])) {
            if ($filters['paid'] === false) {
                $query = $query->notPaid();
            }
        }

        return $query->count();
    }
}
