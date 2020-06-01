<?php declare(strict_types=1);

namespace App\ThirdParty;

interface UserServiceInterface
{
    /**
     * @return array
     */
    public function getUsers(): array;
}
