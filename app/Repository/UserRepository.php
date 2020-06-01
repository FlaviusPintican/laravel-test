<?php declare(strict_types=1);

namespace App\Repository;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    /**
     * @param int $id
     * @throws ModelNotFoundException
     *
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return User::all()->all();
    }

    /**
     * @param string $email
     *
     * @return array
     */
    public function getUsersByEmail(string $email): array
    {
        return User::where('email', $email)->get()->all();
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getUserPosts(int $userId): array
    {
        return User::find($userId)->posts->all();
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getUsersByLimit(int $offset, int $limit): array
    {
        return User::orderBy('id','ASC')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->all();
    }

    /**
     * @param int $userId
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getUserPostsByLimit(int $userId, int $offset, int $limit): array
    {
        return Post::where('user_id', $userId)
            ->orderBy('id','ASC')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->all();
    }
}
