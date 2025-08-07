<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    use HasFactory;
    public $table = "appointments";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }
    public function prescription()
    {
        return $this->hasOne(Prescriptions::class, 'appointment_id', 'id');
    }
    public function patient()
    {
        return $this->hasOne(AddedPatients::class, 'id', 'patient_id');
    }
    public function rating()
    {
        return $this->hasOne(DoctorReviews::class, 'appointment_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(AppointmentDocs::class, 'appointment_id', 'id');
    }
}
