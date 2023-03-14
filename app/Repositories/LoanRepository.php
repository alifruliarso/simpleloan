<?php

namespace App\Repositories;

use App\Models\Loan;

/**
 * Summary of LoanRepository
 */
class LoanRepository extends BaseRepository
{
    public function __construct(Loan $loan)
    {
        $this->model = $loan;
    }

    public function filterAndGet($userId = 0, $page = 1, $limit = 10): mixed
    {
        if ($userId === 0) {
            return new \Illuminate\Database\Eloquent\Collection();
        }
        $query = $this->model->newQuery()->where('user_id', $userId);

        return $query->take($limit)->skip($limit * ($page - 1))->get();
    }
}
