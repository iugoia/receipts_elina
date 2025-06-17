<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receipt_id',
        'text',
        'name'
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereNotRejected(Builder $builder): Builder
    {
        return $builder->where('status', '!=', StatusEnum::REJECTED);
    }

    public function scopeWhereSuccess(Builder $builder): Builder
    {
        return $builder->where('status', StatusEnum::SUCCESS->value);
    }
}
