<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'first_name',
        'last_name',
        'gender',
        'birthdate',
        'phone',
        'email',
        'address',
        'city',
        'emergency_contact_name',
        'emergency_contact_phone',
        'blood_type',
        'health_conditions',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}