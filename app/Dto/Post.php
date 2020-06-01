<?php declare(strict_types=1);

namespace App\Dto;

use InvalidArgumentException;

class Post
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $body;

    /**
     * @param array $post
     */
    public function __construct(array $post)
    {
        if (!isset($post['id'], $post['userId'], $post['title'], $post['body'])) {
            throw new InvalidArgumentException(
                'The following keys are missing ' .
                implode(',', array_diff(['userId', 'title', 'body'], array_keys($post)))
            );
        }

        $this->id = $post['id'];
        $this->userId = $post['userId'];
        $this->title = $post['title'];
        $this->body = $post['body'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}
