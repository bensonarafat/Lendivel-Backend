<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledReminders extends Model
{
    use HasFactory;
    public $table = "scheduled_reminders";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
}
