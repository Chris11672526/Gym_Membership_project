<?php
// GymClass.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    protected $fillable = ['name','trainer_id','capacity'];
}
