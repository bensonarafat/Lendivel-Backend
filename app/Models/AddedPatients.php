<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddedPatients extends Model
{
    use HasFactory;
    public $table = "added_patients";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
