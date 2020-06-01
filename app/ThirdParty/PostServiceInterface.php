<?php declare(strict_types=1);

namespace App\ThirdParty;

interface PostServiceInterface
{
    /**
     * @return array
     */
    public function getPosts(): array;
}
