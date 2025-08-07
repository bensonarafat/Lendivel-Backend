<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Users extends Model
{
    use HasFactory;
    public $table = "users";
    public function patients()
    {
        return $this->hasMany(AddedPatients::class, 'user_id', 'id');
    }

    public function age()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }
}
