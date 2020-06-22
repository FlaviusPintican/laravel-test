<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Image;
use App\Repository\ImageRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageService
{
    /**
     * @var ImageRepository $imageRepository
     */
    private ImageRepository $imageRepository;

    /**
     * @param ImageRepository $imageRepository
     */
    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @return Image
     */
    public function getRandomImage(): Image
    {
        $totalImages = $this->imageRepository->totalImage();

        if ($totalImages === 0) {
            throw new NotFoundHttpException('No image found');
        }

        return $this->imageRepository->getImage(rand(1, $totalImages));
    }

    /**
     * @param string $title
     * @param int    $id
     *
     * @return Image
     */
    public function editImage(string $title, int $id): Image
    {
        $image = $this->getImage($id);
        $image->update(['title' => $title]);

        return $image;
    }

    /**
     * @param int $id
     *
     * @return Image
     */
    public function getImage(int $id): Image
    {
        $image = $this->imageRepository->getImage($id);

        if (null === $image) {
            throw new NotFoundHttpException('Image not found');
        }

        return $image;
    }
}
