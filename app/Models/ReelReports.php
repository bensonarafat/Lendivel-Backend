<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelReports extends Model
{
    use HasFactory;
    public $table = "reel_reports";

    public function reel()
    {
        return $this->hasOne(Reels::class, 'id', 'reel_id');
    }
    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }
}
