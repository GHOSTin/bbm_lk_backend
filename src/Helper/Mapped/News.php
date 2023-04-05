<?php


namespace App\Helper\Mapped;


use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class News
{
    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $imageUrl;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $url;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $title;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $description;

    /**
     * @var DateTime|null
     * @Groups({"show"})
     */
    private $date;

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     */
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     */
    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }
}