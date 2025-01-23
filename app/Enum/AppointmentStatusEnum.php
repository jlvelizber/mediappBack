<?php

namespace App\Enum;

enum AppointmentStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
} 
