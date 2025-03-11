<?php
namespace App\Helpers;

use App\Enum\UserRoleEnum;
use App\Models\User;

class ValidationHelpers
{
    public static function valiateIfSameDoctorAndRole(User $user, $doctorId): bool
    {
        return $user->role == UserRoleEnum::DOCTOR->value && $user->doctor->id == $doctorId;
    }
}