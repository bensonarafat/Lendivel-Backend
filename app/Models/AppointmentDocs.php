<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentDocs extends Model
{
    use HasFactory;
    public $table = "appointment_docs";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
}
