<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    use HasFactory;
    public $table = "faqs";

    public function category()
    {
        return $this->hasOne(FaqCats::class, 'id', 'category_id');
    }
}
