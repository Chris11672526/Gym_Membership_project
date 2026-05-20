<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'description',
        'features',
        'is_active',
    ];
}