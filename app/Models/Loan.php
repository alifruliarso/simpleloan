<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'term', 'request_amount', 'repayment_amount', 'request_at', 'status', 'details', 'approved_by'];

    protected $attributes = [
        'status' => 'pending',
        'approved_by' => 0,
        'details' => '',
    ];

    protected $dates = ['request_at'];

    public $timestamps = true;
}
