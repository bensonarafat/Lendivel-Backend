<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWalletRechargeLogs extends Model
{
    use HasFactory;
    public $table = "user_wallet_recharge_logs";
    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
