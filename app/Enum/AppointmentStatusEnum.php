<?php

namespace App\Enum;

enum AppointmentStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function translateByKey(string $key): string
    {
        return match ($key) {
            self::PENDING->name => __('app.appointments.status.pending'),
            self::COMPLETED->name => __('app.appointments.status.completed'),
            self::CANCELLED->name => __('app.appointments.status.cancelled'),
            default => __('app.appointments.status.pending'),
        };
    }
    public static function translateByValue(string $value): string
    {
        return match ($value) {
            self::PENDING->value => __('app.appointments.status.pending'),
            self::COMPLETED->value => __('app.appointments.status.completed'),
            self::CANCELLED->value => __('app.appointments.status.cancelled'),
            default => __('app.appointments.status.pending'),
        };
    }
}
