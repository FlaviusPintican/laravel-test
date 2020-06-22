<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use Exception;
use App\Models\User;
use http\Env\Request;
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
     * @return bool
     */
    public function deleteUser(int $id): bool;

    /**
     * @param array $fields
     *
     * @return Comment
     */
    public function addComment(array $fields): Comment;
}
