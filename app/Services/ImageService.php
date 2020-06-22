<?php declare(strict_types=1);

namespace App\Services;

use DateTime;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageService implements ImageServiceInterface
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function editImage(string $title, int $id): Image
    {
        $image = $this->getImage($id);
        $image->update(['title' => $title]);

        return $image;
    }

    /**
     * {@inheritDoc}
     */
    public function getImage(int $id): Image
    {
        $image = $this->imageRepository->getImage($id);

        if (null === $image) {
            throw new NotFoundHttpException('Image not found');
        }

        return $image;
    }

    /**
     * {@inheritDoc}
     */
    public function addImage(Request $request): Image
    {
        $image = $request->file('image');
        $userId = $request->user()->id;
        $fileName = time() . '.' . $image->getClientOriginalName();
        Storage::disk('local')
               ->put("images/$userId/" . $fileName, file_get_contents($image->getRealPath()), 'public');

        return $this->imageRepository->addImage([
            'title' => $request->get('title'),
            'image' => $fileName,
            'user_id' => $userId,
            'date' => new DateTime(),
        ]);
    }
}
