<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'full_description',
        'history',
        'popular_dishes',
        'video_url',
        'interesting_facts',
    ];

    protected $casts = [
        'popular_dishes' => 'array',
    ];

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}
