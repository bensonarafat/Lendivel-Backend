<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCats extends Model
{
    use HasFactory;
    public $table = "faq_categories";

    public function faqs()
    {
        return $this->hasMany(Faqs::class, 'category_id', 'id');
    }
}
