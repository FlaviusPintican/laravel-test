<?php declare(strict_types=1);

namespace App\Services;

use App\Dto\Filters;
use App\Models\Comment;
use App\Repository\CommentRepository;
use DateTime;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @param ImageRepository   $imageRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(ImageRepository $imageRepository, CommentRepository $commentRepository)
    {
        $this->imageRepository = $imageRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getRandomImage(): Image
    {
        $imageIds = $this->imageRepository->getImageIds(30, 0);

        if (count($imageIds) === 0) {
            throw new NotFoundHttpException('No image found');
        }

        return $this->getImage($imageIds[array_rand($imageIds)]);
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
            $this->deleteFile($userId, $fileName);
            $this->deleteEmptyDirectory($userId);
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
        $name = $image->image;

        DB::beginTransaction();

        if (($image->comments()->count() > 0 && !$image->comments()->delete()) || !$image->delete()) {
            throw new BadRequestHttpException('Image can not be deleted');
        }

        $isDeleted = $this->deleteFile($userId, $name);

        if (!$isDeleted) {
            DB::rollBack();
            throw new BadRequestHttpException('Image can not be deleted');
        }

        $this->deleteEmptyDirectory($userId);

        DB::commit();
    }

    /**
     * {@inheritDoc}
     */
    public function getImages(Request $request): array
    {
        return $this->imageRepository->getImages(new Filters($request));
    }

    /**
     * {@inheritDoc}
     */
    public function addComment(string $body, int $imageId): Comment
    {
        if (!Image::find($imageId)) {
            throw new NotFoundHttpException('Image not found');
        }

        return $this->commentRepository->addComment([
            'image_id' => $imageId,
            'body' => $body,
            'user_id' => Auth::user()->id,
        ]);
    }

    /**
     * @param int $userId
     *
     * @return void
     */
    private function deleteEmptyDirectory(int $userId) : void
    {
        $directory = sprintf('%s/%d', self::DIRECTORY, $userId);

        if (count(Storage::files($directory)) === 0) {
            Storage::deleteDirectory($directory);
        }
    }

    /**
     * @param int $userId
     * @param string $name
     *
     * @return bool
     */
    private function deleteFile(int $userId, string $name): bool
    {
        return Storage::disk('local')
            ->delete(
                sprintf(
                    '%s/%d/%s',
                    self::DIRECTORY, $userId, $name
                )
            );

    }
}
