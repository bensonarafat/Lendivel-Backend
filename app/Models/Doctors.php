<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    use HasFactory;
    public $table = "doctors";

    public function category()
    {
        return $this->hasOne(DoctorCategories::class, 'id', 'category_id');
    }
    public function bankAccount()
    {
        return $this->hasOne(DoctorBankAccount::class, 'doctor_id', 'id');
    }
    public function reviews()
    {
        return $this->hasMany(DoctorReviews::class, 'doctor_id', 'id');
    }
    public function holidays()
    {
        return $this->hasMany(DoctorHolidays::class, 'doctor_id', 'id');
    }
    public function slots()
    {
        return $this->hasMany(DoctorAppointmentSlots::class, 'doctor_id', 'id');
    }
    public function awards()
    {
        return $this->hasMany(DoctorAwards::class, 'doctor_id', 'id');
    }
    public function services()
    {
        return $this->hasMany(DoctorServices::class, 'doctor_id', 'id');
    }
    public function expertise()
    {
        return $this->hasMany(DoctorExpertise::class, 'doctor_id', 'id');
    }
    public function experience()
    {
        return $this->hasMany(DoctorExperience::class, 'doctor_id', 'id');
    }
    public function serviceLocations()
    {
        return $this->hasMany(DoctorServiceLocations::class, 'doctor_id', 'id');
    }

    public function avgRating()
    {
        return $this->reviews->avg('rating');
    }
}
