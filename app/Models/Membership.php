<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'customer_id',
        'membership_plan_id',
        'start_date',
        'expiration_date',
        'status',
        'notes',
    ];

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}