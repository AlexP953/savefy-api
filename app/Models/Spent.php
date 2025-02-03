<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spent extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'category_id',
        'month_id',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}

