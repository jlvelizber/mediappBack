<?php

namespace App\Repositories\Interface;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email);



    /**
     * @return Model
     */
    public function getDoctors();
}
