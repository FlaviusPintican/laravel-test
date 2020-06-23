<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use App\Models\Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface ImageServiceInterface
{
    /**
     * @throws NotFoundHttpException
     *
     * @return Image
     */
    public function getRandomImage(): Image;

    /**
     * @param string $title
     * @param int $id
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     *
     * @return Image
     */
    public function editImage(string $title, int $id): Image;

    /**
     * @param int $id
     * @throws NotFoundHttpException
     *
     * @return Image
     */
    public function getImage(int $id): Image;

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     *
     * @return Image
     */
    public function addImage(Request $request): Image;

    /**
     * @param int $userId
     * @param int $id
     * @throws NotFoundHttpException
     *
     * @return void
     */
    public function deleteImage(int $userId, int $id): void;

    /**
     * @param Request $request
     *
     * @return Image[]
     */
    public function getImages(Request $request): array;

    /**
     * @param string $body
     * @param int $imageId
     *
     * @return Comment
     */
    public function addComment(string $body, int $imageId): Comment;
}
