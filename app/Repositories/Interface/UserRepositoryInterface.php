<?php

namespace App\Repositories\Interface;
use \Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email);



    /**
     * @return Collection
     */
    public function getDoctors(): ?Collection;
}
