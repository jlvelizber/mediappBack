<?php

namespace App\Services;

use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        // Validar y procesar datos aquí si es necesario
        return $this->userRepository->create($data);
    }


    public function listAllUsers()
    {
        // Validar y procesar datos aquí si es necesario
        return $this->userRepository->all();
    }

    /**
     * Summary of getDoctors
     * @return Collection|null
     */
    public function getDoctors(): ?Collection
    {
        return $this->userRepository->getDoctors();
    }
}
