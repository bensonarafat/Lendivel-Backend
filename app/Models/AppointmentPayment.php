<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPayment extends Model
{

    use HasFactory;
    public $table = "appointment_payments";

    protected $fillable = [
        'appointment_id',
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'paid_at'
    ];
}
