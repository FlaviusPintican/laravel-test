<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Services\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController
{
    /**
     * @var PostServiceInterface
     */
    private PostServiceInterface $postService;

    /**
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param int $postId
     * @return JsonResponse
     */
    public function getPostCommnents(int $postId): JsonResponse
    {
        return response()->json($this->postService->getPostCommnents($postId));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPosts(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string',
        ]);

        return response()->json($this->postService->getPosts($validatedData));
    }

}
