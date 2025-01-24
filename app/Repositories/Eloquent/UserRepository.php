<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Enum\UserRoleEnum;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * @return Model
     */
    public function getDoctors()
    {
        return $this->model->where('role', UserRoleEnum::ADMIN->value)->get();
    }
}
