<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    use HasFactory;
    public $table = "connections";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctors::class, 'id', 'doctor_id');
    }
}
