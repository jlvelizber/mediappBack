<?php

namespace App\Repositories\Interface;

interface UserRepositoryInterface
{
    public function findByEmail(string $email);
}
