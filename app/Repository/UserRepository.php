<?php declare(strict_types=1);

namespace App\Repository;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    /**
     * @param string $searchValue
     *
     * @return User[]
     */
    public function getUsersByCriteria(string $searchValue): array
    {
        return User::where('username', 'like', '%' . $searchValue . '%')
            ->orWhere('first_name', 'like', '%' . $searchValue . '%')
            ->orWhere('family_name', 'like', '%' . $searchValue . '%')
            ->orWhere('email', 'like', '%' . $searchValue . '%')
            ->orWhere('phone_number', 'like', '%' . $searchValue . '%')
            ->get()
            ->all();
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return User::all()->all();
    }

    /**
     * @param array $fields
     *
     * @return User
     */
    public function addUser(array $fields): User
    {
        return User::create($fields);
    }

    /**
     * @param int $id
     * @throws ModelNotFoundException
     *
     * @return null|User
     */
    public function getUser(int $id): ?User
    {
        return User::find($id);
    }
}
