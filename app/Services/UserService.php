<?php declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Cache;

class UserService implements UserServiceInterface
{
    /**
     * @var string
     */
    private const EXPIRE_TIME = 500; // in seconds

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserById(int $id): User
    {
        return $this->userRepository->getUserById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsers(array $data): array
    {
        $email = $data['email'] ?? null;

        if (null === $email) {
            return $this->userRepository->getUsers();
        }

        return $this->userRepository->getUsersByEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserPosts(int $userId): array
    {
        return Cache::remember("users-$userId" . uniqid(), self::EXPIRE_TIME, function () use ($userId): array {
            return $this->userRepository->getUserPosts($userId);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getUsersPosts(): array
    {
        return Cache::remember('users' . uniqid(), self::EXPIRE_TIME, function () : array {
            $users = $this->userRepository->getUsersByLimit(0, 10);
            $posts = [];

            foreach ($users as $user) {
                $posts[] = $this->userRepository->getUserPostsByLimit($user->id, 0, 50);
            }

            return $posts;
        });
    }
}
