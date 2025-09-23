<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $table = "subscriptions";

    protected $fillable = [
        'user_id',
        'doctor_id',
        'service_amount',
        'discount_amount',
        'subtotal',
        'total_tax_amount',
        'payable_amount',
        'payment_status',
        'connection_id',
    ];
}
