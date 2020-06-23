<?php declare(strict_types=1);

namespace App\Services;

use App\Dto\Filters;
use DateTime;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageService implements ImageServiceInterface
{
    /**
     * @var string
     */
    private const DIRECTORY = 'images';

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

        if (!$image->update(['title' => $title])) {
            throw new BadRequestHttpException('Image was not updated');
        }

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

        $isSaved = Storage::disk('local')
           ->put(
               sprintf(
               '%s/%d/%s',
               self::DIRECTORY, $userId, $fileName
               ),
               file_get_contents($image->getRealPath()),
               'public'
           );

        if (!$isSaved) {
            throw new BadRequestHttpException('Image was not saved');
        }

        $image = $this->imageRepository->addImage([
            'title' => $request->get('title'),
            'image' => $fileName,
            'user_id' => $userId,
            'date' => new DateTime(),
        ]);

        if (!$image) {
            Storage::disk('local')
               ->delete(
                   sprintf(
                       '%s/%d/%s',
                       self::DIRECTORY, $userId, $fileName
                   )
               );
            throw new BadRequestHttpException('Image was not saved');
        }

        return $image;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteImage(int $userId, int $id): void
    {
        $image = $this->getImage($id);
        $name = $image->name;

        DB::beginTransaction();

        if (!$image->delete()) {
            throw new BadRequestHttpException('Image can not be deleted');
        }

        $isDeleted = Storage::disk('local')
           ->delete(
               sprintf(
                   '%s/%d/%s',
                   self::DIRECTORY, $userId, $name
               )
           );

        if (!$isDeleted) {
            DB::rollBack();
        }

        DB::commit();
    }

    /**
     * {@inheritDoc}
     */
    public function getImages(Request $request): array
    {
        return $this->imageRepository->getImages(new Filters($request));
    }
}
