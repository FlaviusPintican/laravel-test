<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Image as ImageResource;
use App\Models\Comment;
use App\Models\Image;
use App\Services\ImageService;
use App\Services\ImageServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    /**
     * @var ImageServiceInterface $imageService
     */
    private ImageServiceInterface $imageService;

    /**
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @return Image
     */
    public function getRandomImage(): Image
    {
        return $this->imageService->getRandomImage();
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return Image
     */
    public function editImage(Request $request, int $id): Image
    {
        $request->validate(
            [
                'title' => 'required|string|min:5',
            ],
            $request->all()
        );

        return $this->imageService->editImage($request->get('title'), $id);
    }

    /**
     * @param int $id
     *
     * @return Image
     */
    public function getImage(int $id): Image
    {
        return $this->imageService->getImage($id)->load('comments');
    }

    /**
     * @param Request $request
     *
     * @return Image
     */
    public function addImage(Request $request): Image
    {
        $request->validate(
            [
                'title' => 'required|string|min:5',
                'image' => 'required'
            ],
            $request->all()
        );

        return $this->imageService->addImage($request);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteImage(Request $request, int $id): JsonResponse
    {
        $this->imageService->deleteImage($request->user()->id, $id);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getImages(Request $request): array
    {
        $images = $this->imageService->getImages($request);

        return array_map(
            fn (Image $image): ImageResource => new ImageResource($image->load('comments')), $images
        );
    }

    /**
     * @param Request $request
     * @param int $imageId
     *
     * @return Comment
     */
    public function addComment(Request $request, int $imageId): Comment
    {
        $request->validate(
            [
                'body' => 'required|string|max:190',
            ]
        );
        return $this->imageService->addComment($request->get('body'), $imageId);
    }
}
