<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformEarningHistory extends Model
{
    use HasFactory;
    public $table = "platform_earning_history";

    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }
}
