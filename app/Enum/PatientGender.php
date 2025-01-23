<?php

namespace App\Enum;

enum PatientGender : string
{
    case MALE = 'male';

    case FEMALE = 'female';

    case OTHER = 'other';
}
