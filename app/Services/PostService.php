<?php declare(strict_types=1);

namespace App\Services;

use App\Repository\PostRepository;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getPostCommnents(int $postId): array
    {
        return $this->postRepository->getPostCommnents($postId);
    }

    /**
     * {@inheritDoc}
     */
    public function getPosts(array $data): array
    {
        $title = $data['title'] ?? null;

        if (null === $title) {
            return $this->postRepository->getPosts();
        }

        return $this->postRepository->getPostsByTitle($title);
    }
}
