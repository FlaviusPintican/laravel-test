<?php declare(strict_types=1);

namespace App\ThirdParty;

use App\Dto\Post;
use Illuminate\Support\Facades\Http;

class PostService implements PostServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getPosts(): array
    {
        return $this->mapPosts(Http::get(config('BASE_URL')  . '/posts')->json());
    }

    /**
     * @param array $posts
     *
     * @return array
     */
    private function mapPosts(array $posts): array
    {
        return array_map(fn (array $post) => (new Post($post))->toArray(), $posts);
    }
}
