<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorNote extends Model
{
    use HasFactory;

    public $table = "doctor_notes";

    protected $fillable = [
        'appointment_id',
        'user_id',
        'patient_complaint',
        'brief_history',
        'diagnosis',
        'investigations',
        'treatment_plan',
        'medications_prescribed',
        'follow_up_instructions',
        'note_date',
    ];

    protected $casts = [
        'note_date' => 'date'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointments::class);
    }
}
