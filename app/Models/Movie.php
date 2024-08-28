<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'published_date',
        'image'
    ];

    /**
     * @param $value
     * @return string
     */
    public function getImageAttribute($value): string
    {
        return env('APP_URL') . '/storage/' . $value;
    }
}
