<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spent extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}

