<?php


namespace App\Helper\Mapped;


use Symfony\Component\Serializer\Annotation\SerializedName;

class Point
{
    /**
     * @var integer
     */
    private $point;

    /**
     * @var integer
     */
    private $pointNumber;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var boolean
     */
    private $isAvailableComment;

    public function __construct(int $point, int $pointNumber, $comment)
    {
        $this->point = $point;
        $this->pointNumber = $pointNumber;
        $this->comment = $comment;
        $this->isAvailableComment = is_null($comment) ? false : true;
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return $this->point;
    }

    /**
     * @param int $point
     */
    public function setPoint(int $point): void
    {
        $this->point = $point;
    }

    /**
     * @return int
     */
    public function getPointNumber(): int
    {
        return $this->pointNumber;
    }

    /**
     * @param int $pointNumber
     */
    public function setPointNumber(int $pointNumber): void
    {
        $this->pointNumber = $pointNumber;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return bool
     */
    public function getIsAvailableComment(): bool
    {
        return $this->isAvailableComment;
    }

    /**
     * @param bool $isAvailableComment
     */
    public function setIsAvailableComment(bool $isAvailableComment): void
    {
        $this->isAvailableComment = $isAvailableComment;
    }
}