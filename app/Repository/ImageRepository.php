<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\Filters;
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

    /**
     * @param Filters $filters
     *
     * @return array
     */
    public function getImages(Filters $filters): array
    {
        $query = Image::query()
                      ->select()
                      ->join('user', 'user.id', '=', 'image.user_id');

        if (null !== $filters['user_id']) {
            $query->where('image.user_id' , '=', $filters->getUserId());
        }

        if (null !== $filters['title']) {
            $query->where('image.title', $filters->getTitle());
        }

        if (null !== $filters['uploader_name']) {
            $query->where('user.username', $filters->getUploaderName())
                ->orWhere('user.first_name', $filters->getUploaderName())
                ->orWhere('user.family_name', $filters->getUploaderName());
        }

        return $query->orderBy('date', $filters->getDirection())
            ->offset($filters->getOffset())
            ->limit($filters->getLimit())
            ->get()
            ->all();
    }
}
