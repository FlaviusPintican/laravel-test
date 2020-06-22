<?php declare(strict_types=1);

namespace App\Repository;

use App\Models\Comment;

class CommentRepository
{
    /**
     * @param array $fields
     *
     * @return Comment
     */
    public function addComment(array $fields): Comment
    {
        return Comment::create($fields);
    }
}
