<?php declare(strict_types=1);

namespace App\ThirdParty;

interface CommentServiceInterface
{
    /**
     * @return array
     */
    public function getComments(): array;
}
