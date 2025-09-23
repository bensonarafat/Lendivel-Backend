<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChronicCondition extends Model
{
    use HasFactory;

    public $table = "chronic_conditions";

    protected $fillable = [
        'user_id',
        'condition_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
