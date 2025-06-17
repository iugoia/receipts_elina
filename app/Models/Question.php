<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    const NEW = 'new';
    const ANSWERED = 'answered';

    protected $fillable = [
        'phone',
        'name',
        'status'
    ];

    public function getStatusName(): string
    {
        return match ($this->status) {
            self::NEW => 'Новый',
            self::ANSWERED => 'Отвечен',
        };
    }
}
