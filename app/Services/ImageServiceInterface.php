<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\Request;

interface ImageServiceInterface
{
    /**
     * @return Image
     */
    public function getRandomImage(): Image;

    /**
     * @param string $title
     * @param int    $id
     *
     * @return Image
     */
    public function editImage(string $title, int $id): Image;

    /**
     * @param int $id
     *
     * @return Image
     */
    public function getImage(int $id): Image;

    /**
     * @param Request $request
     *
     * @return Image
     */
    public function addImage(Request $request): Image;
}
