<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'loan_id', 'due_date', 'due_amount', 'paid_amount', 'status', 'paid_at'];

    protected $attributes = [
        'status' => 'pending',
        'paid_amount' => 0,
    ];

    protected $dates = ['due_date', 'paid_at'];

    public $timestamps = true;

    public function scopeNotPaid($query)
    {
        return $query->whereColumn('paid_amount', '<', 'due_amount');
    }
}
