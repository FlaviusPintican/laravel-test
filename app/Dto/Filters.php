<?php declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\Request;

class Filters
{
    /**
     * @var int
     */
    private int $limit;

    /**
     * @var int
     */
    private int $offset;

    /**
     * @var int|null
     */
    private ?int $userId;

    /**
     * @var string
     */
    private string $direction;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $uploaderName;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->limit = $request->query->get('limit', 10);
        $this->offset = $request->query->get('offset', 0);
        $this->userId = $request->user() ? $request->user()->id : null;
        $this->direction = $request->query->get('direction', 'ASC');
        $this->title = $request->query->get('title', '');
        $this->uploaderName = $request->query->get('uploader_name', '');
    }

    /**
     * @return int
     */
    public function getLimit() : int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset() : int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getUserId() : ?int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getDirection() : string
    {
        return $this->direction;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUploaderName() : string
    {
        return $this->uploaderName;
    }
}
