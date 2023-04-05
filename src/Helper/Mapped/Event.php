<?php

namespace App\Helper\Mapped;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class Event
{
    /** @var string
     *  @Groups({"show", "index"})
     */
    protected $id;

    /** @var string|null
     *  @Groups({"show", "index"})
     */
    protected $name;

    /** @var string|null
     *  @Groups({"show", "index"})
     */
    protected $fullName;

    /** @var string|null
     *  @Groups({"show"})
     */
    protected $description;

    /** @var string|null
     *  @Groups({"show", "index"})
     */
    protected $type;

    /** @var string|null
     *  @Groups({"show"})
     */
    protected $period;

    /** @var DateTime|null
     *  @Groups({"show", "index"})
     */
    protected $date;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string|null $fullName
     */
    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getPeriod(): ?string
    {
        return $this->period;
    }

    /**
     * @param string|null $period
     */
    public function setPeriod(?string $period): void
    {
        $this->period = $period;
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
