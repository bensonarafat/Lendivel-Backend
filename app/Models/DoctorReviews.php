<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorReviews extends Model
{
    use HasFactory;
    public $table = "doctor_reviews";
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }
    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
}
