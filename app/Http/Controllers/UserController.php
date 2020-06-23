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
     * @return JsonResponse
     */
    public function addUser(Request $request): JsonResponse
    {
        return $this->errors($request, [
            'username' => 'required|unique:user,username|max:50',
            'password' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'family_name' => 'required|string|max:50',
            'email' => 'required|string|unique:user,email|max:50',
            'phone_number' => 'required|string|unique:user,phone_number|max:50',
        ]) ?? new JsonResponse($this->userService->addUser($request->all()));
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function editUser(Request $request, int $id): JsonResponse
    {
        return $this->errors($request, [
            'username' => 'sometimes|required|unique:user,username|max:50',
            'password' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'family_name' => 'required|string|max:50',
            'email' => 'sometimes|required|string|unique:user,email|max:50',
            'phone_number' => 'sometimes|required|string|unique:user,phone_number|max:50',
        ]) ?? new JsonResponse($this->userService->editUser($request->all(), $id));
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
        $errors = $this->errors($request, [
            'email'    => 'required|email',
            'password' => 'required|alphaNum|min:4',
        ]);

        if ($errors) {
            return $errors;
        }

        if (!Auth::attempt($request->all())) {
            throw new UnauthorizedHttpException('Unable to login');
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

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addComment(Request $request): JsonResponse
    {
        return $this->errors($request, [
            'image_id' => 'required|integer',
            'body' => 'required|string|max:190',
        ]) ?? new JsonResponse($this->userService->addComment($request->all()));
    }
}
