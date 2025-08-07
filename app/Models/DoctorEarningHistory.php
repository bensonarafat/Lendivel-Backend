<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorEarningHistory extends Model
{
    use HasFactory;
    public $table = "doctor_earning_history";

    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
}
