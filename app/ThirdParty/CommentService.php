<?php declare(strict_types=1);

namespace App\ThirdParty;

use App\Dto\Comment;
use Illuminate\Support\Facades\Http;

class CommentService implements CommentServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getComments(): array
    {
        return $this->mapComments(Http::get(config('BASE_URL') . '/comments')->json());
    }

    /**
     * @param array $comments
     *
     * @return array
     */
    private function mapComments(array $comments): array
    {
        return array_map(fn (array $comment) => (new Comment($comment))->toArray(), $comments);
    }
}
