<?php

namespace App\Enum;

enum WayNotificationEnum: string
{
    case EMAIL = 'email';
    case WHATSAPP = 'whatsapp';
    case BOTH = 'both';

    public static function translateByKey(string $key): string
    {
        return match ($key) {
            self::EMAIL->name => __('app.notifications.way.email'),
            self::WHATSAPP->name => __('app.notifications.way.whatsapp'),
            self::BOTH->name => __('app.notifications.way.both'),
            default => __('app.notifications.way.both'),
        };
    }
}
