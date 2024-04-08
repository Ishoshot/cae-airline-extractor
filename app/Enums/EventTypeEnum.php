<?php

declare(strict_types=1);

namespace App\Enums;

enum EventTypeEnum: string
{
    case CheckIn = 'CI';
    case CheckOut = 'CO';
    case DayOff = 'DO';
    case StandBy = 'SBY';
    case Flight = 'FLT';
    case Unknown = 'UNK';


    public static function toArray(): array
    {
        return array_column(EventTypeEnum::cases(), 'value');
    }
}
