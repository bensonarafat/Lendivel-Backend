<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    public $table = "admin_user";
    public $primaryKey = 'user_id';
    public $timestamps = false;
}
