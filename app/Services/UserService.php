<?php declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository $userRepository
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
    public function getUsers(string $searchValue): array
    {
        if (strlen($searchValue) === 0) {
            return $this->userRepository->getUsers();
        }

        return $this->userRepository->getUsersByCriteria($searchValue);
    }

    /**
     * @inheritDoc
     */
    public function addUser(array $fields): User
    {
        return $this->userRepository->addUser(array_merge($fields, ['password' => Hash::make($fields['password'])]));
    }

    /**
     * {@inheritDoc}
     */
    public function editUser(array $fields, int $id): User
    {
        $user = $this->getUser($id);

        $user->update($fields);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteUser(int $id): void
    {
        $user = $this->getUser($id);
        DB::beginTransaction();

        if (
            ($user->comments()->count() > 0 && !$user->comments()->delete())
            || ($user->images()->count() > 0 && !$user->images()->delete())
            || !$user->delete()
        ) {
            DB::rollBack();
            throw new BadRequestHttpException('User can not be deleted');
        }

        $this->logout();
        DB::commit();
    }

    /**
     * @param int $id
     * @throws NotFoundHttpException
     *
     * @return User
     */
    public function getUser(int $id): User
    {
        $user = $this->userRepository->getUser($id);

        if (null === $user) {
            throw new NotFoundHttpException('Model not found');
        }

        return $user;
    }

    /**
     * @return void
     */
    public function logout(): void
    {

        $tokens = Auth::user()->tokens;

        foreach ($tokens as $token) {
            $token->revoke();
        }
    }
}
