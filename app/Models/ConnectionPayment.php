<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionPayment extends Model
{
    use HasFactory;

    use HasFactory;
    public $table = "connection_payments";

    protected $fillable = [
        'connection_id',
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'paid_at'
    ];
}
