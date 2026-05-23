<?php
// Session.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['customer_id','class_id','attended_at'];
}