<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\Filters;
use App\Models\Image;

class ImageRepository
{

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return int[]
     */
    public function getImageIds(int $limit, int $offset): array
    {
        return Image::take($limit)->skip($offset)->pluck('id')->all();
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
                      ->select('image.*')
                      ->join('user', 'user.id', '=', 'image.user_id');

        if (null !== $filters->getUserId()) {
            $query->where('image.user_id', $filters->getUserId());
        }

        if (strlen($filters->getTitle()) > 0) {
            $query->where('image.title', 'LIKE',  '%' . $filters->getTitle() . '%');
        }

        if (strlen($filters->getUploaderName()) > 0) {
            $query->where('user.username', 'LIKE', '%' . $filters->getUploaderName() . '%')
                ->orWhere('user.first_name', 'LIKE', '%' . $filters->getUploaderName() . '%')
                ->orWhere('user.family_name', 'LIKE', '%' . $filters->getUploaderName() . '%');
        }

        return $query->orderBy('image.title', $filters->getDirection())
            ->orderBy('user.first_name', $filters->getDirection())
            ->offset($filters->getOffset())
            ->limit($filters->getLimit())
            ->with('comments')
            ->get()
            ->all();
    }
}
