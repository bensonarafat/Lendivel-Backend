<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfile extends Model
{
    use HasFactory;


    public $table = "health_profiles";

    protected $fillable = [
        'user_id',
        'height_cm',
        'weight_kg',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
