<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Receipt extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        'ingredients' => 'array'
    ];

    protected $guarded = [];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cuisine(): BelongsTo
    {
        return $this->belongsTo(Cuisine::class);
    }

    public function scopeWhereAuthUser(Builder $query): Builder
    {
        return $query->where('user_id', auth()->id());
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'receipt_id', 'user_id');
    }

    public function scopeWhereApproved(Builder $query): Builder
    {
        return $query->where('status', StatusEnum::SUCCESS->value);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCountry()
    {
        return trans("countries." . $this->country_code, [], 'ru');
    }
}
