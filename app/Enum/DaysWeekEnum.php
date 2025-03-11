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


    public static function getKeyByIndex(int $index): string
    {
        return match ($index) {
            0 => strtolower(self::SUNDAY->name),
            1 => strtolower(self::MONDAY->name),
            2 => strtolower(self::TUESDAY->name),
            3 => strtolower(self::WEDNESDAY->name),
            4 => strtolower(self::THURSDAY->name),
            5 => strtolower(self::FRIDAY->name),
            6 => strtolower(self::SATURDAY->name),
            default => strtolower(self::SUNDAY->name),
        };
    }
}
