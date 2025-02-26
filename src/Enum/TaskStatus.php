<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = "A faire";
    case DOING = "En cours";
    case FINISHED= 'Terminé';

    public static function values():array
    {
        return array_column (self::cases(), 'value');
    }
}