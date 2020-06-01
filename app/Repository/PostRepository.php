<?php declare(strict_types=1);

namespace App\Repository;

use App\Models\Post;

class PostRepository
{
    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return Post::all()->all();
    }

    /**
     * @param string $title
     *
     * @return array
     */
    public function getPostsByTitle(string $title): array
    {
        return Post::where('title', 'LIKE', '%' . $title . '%')->get()->all();
    }

    /**
     * @param int $postId
     *
     * @return array
     */
    public function getPostCommnents(int $postId): array
    {
       return Post::find($postId)->comments->all();
    }
}
