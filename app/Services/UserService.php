<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use App\Models\Image;
use App\Models\User;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\TestFixture\C;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserServiceInterface
{
    use Login;

    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @var CommentRepository $commentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @param UserRepository    $userRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(UserRepository $userRepository, CommentRepository $commentRepository)
    {
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
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
        $this->check();

        return $this->userRepository->addUser(array_merge($fields, ['password' => Hash::make($fields['password'])]));
    }

    /**
     * {@inheritDoc}
     */
    public function editUser(array $fields, int $id): User
    {
        $this->check();

        $user = $this->getUser($id);

        $user->update($fields);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteUser(int $id): bool
    {
        $this->check();

        return $this->getUser($id)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function addComment(array $fields): Comment
    {
        $this->check();

        if (Image::find($fields['image_id'])) {
            throw new NotFoundHttpException('Image not found');
        }

        $fields['user_id'] = Auth::user()->id;

        return $this->commentRepository->addComment($fields);
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

        if ( null === $user ) {
            throw new NotFoundHttpException('Model not found');
        }

        return $user;
    }
}
