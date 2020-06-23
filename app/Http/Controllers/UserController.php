<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserController extends Controller
{
    /**
     * @var UserServiceInterface $userService
     */
    private UserServiceInterface $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     *
     * @return User[]
     */
    public function getUsers(Request $request): array
    {
        return $this->userService->getUsers($request->query('searchValue', ''));
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function getUser(int $id): User
    {
        return $this->userService->getUser($id);
    }

    /**
     * @param Request $request
     *
     * @return User
     */
    public function addUser(Request $request): User
    {
        $fields = $request->validate(
            [
                'username' => 'required|unique:user,username|max:50',
                'password' => 'required|string|max:100',
                'first_name' => 'required|string|max:100',
                'family_name' => 'required|string|max:50',
                'email' => 'required|string|unique:user,email|max:50',
                'phone_number' => 'required|string|unique:user,phone_number|max:50',
            ],
            $request->all()
        );

        return $this->userService->addUser($fields);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return User
     */
    public function editUser(Request $request, int $id): User
    {
        $fields = $request->validate(
            [
                'username' => 'sometimes|required|unique:user,username|max:50',
                'password' => 'required|string|max:100',
                'first_name' => 'required|string|max:100',
                'family_name' => 'required|string|max:50',
                'email' => 'sometimes|required|string|unique:user,email|max:50',
                'phone_number' => 'sometimes|required|string|unique:user,phone_number|max:50',
            ],
            $request->all()
        );

        return $this->userService->editUser($fields, $id);
    }

    /**
     * @param int $id
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function deleteUser(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required|alphaNum|min:4',
        ]);

        if (!Auth::attempt($request->all())) {
            throw new UnauthorizedHttpException('', 'Invalid username or password');
        }

        return new JsonResponse(['token' => Auth::user()->createToken(User::class)->accessToken]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->userService->logout();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
