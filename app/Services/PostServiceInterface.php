<?php

namespace App\Services;

interface PostServiceInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function getPosts(array $data): array;
}
