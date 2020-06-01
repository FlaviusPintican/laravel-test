<?php declare(strict_types=1);

namespace App\Dto;

use InvalidArgumentException;

class Comment
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var int
     */
    private int $postId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $body;

    /**
     * @param array $comment
     */
    public function __construct(array $comment)
    {
        if (!isset($comment['id'], $comment['postId'], $comment['name'], $comment['email'], $comment['body'])) {
            throw new InvalidArgumentException(
                'The following keys are missing ' .
                implode(',', array_diff(['id', 'postId', 'name', 'email', 'body'], array_keys($comment)))
            );
        }

        $this->id = $comment['id'];
        $this->postId = $comment['postId'];
        $this->name = $comment['name'];
        $this->email = $comment['email'];
        $this->body = $comment['body'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->postId,
            'name' => $this->name,
            'email' => $this->email,
            'body' => $this->body,
        ];
    }
}
