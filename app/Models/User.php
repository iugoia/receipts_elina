<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_activity_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'last_activity_at'  => 'datetime'
    ];

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function canAccessChat(string $chatId): bool
    {
        $chat = Chat::findOrFail($chatId);
        return $this->id == $chat->user_id || $this->id == $chat->admin_id;
    }

    public function updateLastAction(): void
    {
        $this->update([
            'last_activity_at' => now()
        ]);
    }

    public function isOnline(): bool
    {
        return $this->last_activity_at && $this->last_activity_at->addMinutes(2)->isFuture();
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Receipt::class, 'user_favorites', 'user_id', 'receipt_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
