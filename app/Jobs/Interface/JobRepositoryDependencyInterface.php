<?php

namespace App\Jobs\Interface;

use App\Repositories\Interface\RootRepositoryInterface;

interface JobRepositoryDependencyInterface
{

    public function setDependencies(RootRepositoryInterface ...$rootRepositoryInterfaces);

}