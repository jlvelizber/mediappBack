<?php

namespace App\Enum;

enum DaysWeekEnum: string
{
    case MONDAY = 'app.days.monday';
    case TUESDAY = 'app.days.tuesday';
    case WEDNESDAY = 'app.days.wednesday';
    case THURSDAY = 'app.days.thursday';
    case FRIDAY = 'app.days.friday';
    case SATURDAY = 'app.days.saturday';
    case SUNDAY = 'app.days.sunday';


    public static function toArray(): array
    {
        return [
            self::MONDAY->name => self::MONDAY->value,
            self::TUESDAY->name => self::TUESDAY->value,
            self::WEDNESDAY->name => self::WEDNESDAY->value,
            self::THURSDAY->name => self::THURSDAY->value,
            self::FRIDAY->name => self::FRIDAY->value,
            self::SATURDAY->name => self::SATURDAY->value,
            self::SUNDAY->name => self::SUNDAY->value,
        ];
    }
}
