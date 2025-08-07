<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescriptions extends Model
{
    use HasFactory;
    public $table = "prescriptions";
    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
