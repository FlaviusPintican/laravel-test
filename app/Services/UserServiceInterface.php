<?php declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{
    /**
     * @param int $id
     *
     * @return User
     */
    public function getUserById(int $id): User;

    /**
     * @param array $data
     *
     * @return array
     */
    public function getUsers(array $data): array;

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getUserPosts(int $userId): array;

    /**
     * @return array
     */
    public function getUsersPosts(): array;
}
