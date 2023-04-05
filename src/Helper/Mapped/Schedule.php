<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class Schedule
{
    /** @var \DateTimeInterface|null
     * @Groups({"show"})
     */
    private $date;

    /** @var integer|null
     * @Groups({"show"})
     */
    private $week;

    /** @var string|null
     * @Groups({"show"})
     */
    private $group;

    /** @var array
     */
    private $lessons;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return integer|null
     */
    public function getWeek(): ?int
    {
        return $this->week;
    }

    /**
     * @param integer|null $week
     */
    public function setWeek(?int $week): void
    {
        $this->week = $week;
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     */
    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }

    /**
     * @return array
     */
    public function getLessons(): array
    {
        return $this->lessons;
    }

    /**
     * @param array $lessons
     */
    public function setLessons(array $lessons): void
    {
        $this->lessons = $lessons;
    }
}
