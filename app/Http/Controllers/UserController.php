<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserServiceInterface
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
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'sometimes|required|email',
        ]);

        return response()->json($this->userService->getUsers($validatedData));
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getUser(int $id): JsonResponse
    {
        return response()->json($this->userService->getUserById($id));
    }

    /**
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function getUserPosts(int $userId): JsonResponse
    {
        return response()->json($this->userService->getUserPosts($userId));
    }

    /**
     * @return JsonResponse
     */
    public function getUsersPosts(): JsonResponse
    {
        return response()->json($this->userService->getUsersPosts());
    }
}
