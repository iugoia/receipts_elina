<?php

namespace App\Enums;

enum StatusEnum: string
{
    case NEW = 'new';
    case SUCCESS = 'success';
    case REJECTED = 'rejected';

    public static function getTitle(string $status): string
    {
        $array = [
            'new'      => 'Новый',
            'success'  => 'Одобрено',
            'rejected' => 'Отклонено'
        ];
        return $array[$status];
    }
}
