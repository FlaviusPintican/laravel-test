<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Image;
use App\Services\ImageService;
use App\Services\ImageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    /**
     * @var ImageServiceInterface $imageService
     */
    private ImageService $imageService;

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
     * @return JsonResponse
     */
    public function editImage(Request $request, int $id): JsonResponse
    {
        return $this->errors($request, [
            'title' => 'required|string|min:5',
        ]) ?? new JsonResponse($this->imageService->editImage($request->get('title'), $id));
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
     * @return JsonResponse
     */
    public function addImage(Request $request): JsonResponse
    {
        return $this->errors($request, [
                'title' => 'required|string|min:5',
                'image' => 'required'
        ]) ?? new JsonResponse($this->imageService->addImage($request));
    }
}
