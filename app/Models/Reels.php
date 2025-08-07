<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reels extends Model
{
    use HasFactory;
    public $table = "reels";

    public function comments()
    {
        return $this->hasMany(ReelComments::class, 'reel_id', 'id');
    }
    public function likes()
    {
        return $this->hasMany(ReelLikes::class, 'reel_id', 'id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }
}
