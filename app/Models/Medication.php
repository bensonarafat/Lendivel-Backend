<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    public $table = "medications";

    protected $fillable = [
        'user_id',
        'medication_name',
        'dosage'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
