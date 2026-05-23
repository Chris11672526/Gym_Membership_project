<?php
// Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['membership_id','amount','paid_at','status'];
}