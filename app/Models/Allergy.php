<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    public $table = "allergies";

    protected $fillable = [
        'user_id',
        'allergy',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
