<?php
// Membership.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['customer_id','plan_id','start_date','end_date','status'];
}