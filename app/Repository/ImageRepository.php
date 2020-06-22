<?php declare(strict_types=1);

namespace App\Repository;

use App\Models\Image;

class ImageRepository
{
    /**
     * @return int
     */
    public function totalImage(): int
    {
        return Image::count();
    }

    /**
     * @param int $id
     *
     * @return Image|null
     */
    public function getImage(int $id): ?Image
    {
        return Image::find($id);
    }

    /**
     * @param array $fields
     *
     * @return Image
     */
    public function addImage(array $fields): Image
    {
        return Image::create($fields);
    }
}
