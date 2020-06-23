<?php declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface UserServiceInterface
{
    /**
     * @param string $searchValue
     *
     * @return User[]
     */
    public function getUsers(string $searchValue): array;

    /**
     * @param array $fields
     *
     * @return User
     */
    public function addUser(array $fields): User;

    /**
     * @param array $fields
     * @param int   $id
     * @throws ModelNotFoundException
     *
     * @return User
     */
    public function editUser(array $fields, int $id): User;

    /**
     * @param int $id
     * @throws Exception
     *
     * @return void
     */
    public function deleteUser(int $id): void;
}
