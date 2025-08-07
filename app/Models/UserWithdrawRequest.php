<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWithdrawRequest extends Model
{
    use HasFactory;
    public $table = "user_withdraw_request";

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
