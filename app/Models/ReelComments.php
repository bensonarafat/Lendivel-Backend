<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelComments extends Model
{
    use HasFactory;
    public $table = "reel_comments";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }

}
