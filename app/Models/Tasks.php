<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    public $table = "tasks";

    protected $fillable = [
        'id',
        'appointment_id',
        'user_id',
        'doctor_id',
        'title',
        'description',
        'repeats',
        'start_date',
        'end_date',
        'notes',
        'status'
    ];

    public function appointment()
    {
        return $this->hasOne(Appointments::class, 'id', 'appointment_id');
    }
}
