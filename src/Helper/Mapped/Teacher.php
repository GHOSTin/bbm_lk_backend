<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use DateTime;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class Teacher extends AbstractUser
{
    /** @var string|null
     * @Groups({"show"})
     */
    private $firstName;

    /** @var string|null
     * @Groups({"show"})
     */
    private $lastName;

    /** @var string|null
     * @Groups({"show"})
     */
    private $sex;

    /** @var string|null
     * @Groups({"show"})
     */
    private $type;

    /**
     * @var UserInterests[]|null
     * @SWG\Property(property="interests", type="array", @SWG\Items(ref=@Model(type=UserInterests::class)))
     */
    private $interests;

    /** @var string|null
     * @Groups({"show"})
     */
    private $position;

    /** @var string|null
     * @Groups({"show"})
     */
    private $nameSubject;

    /** @var integer|null
     * @Groups({"show"})
     */
    private $experience;

    /** @var string|null
     * @Groups({"show"})
     */
    private $speciality;

    public function __construct()
    {
        $this->role = AbstractUserRole::ROLE_TEACHER;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * @param string|null $sex
     */
    public function setSex(?string $sex): void
    {
        $this->sex = $sex;
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
     * @return UserInterests[]|null
     */
    public function getInterests(): ?array
    {
        return $this->interests;
    }

    /**
     * @param UserInterests[]|null $interests
     */
    public function setInterests(?array $interests): void
    {
        $this->interests = $interests;
    }

    /**
     * @return int|null
     */
    public function getExperience(): ?int
    {
        return $this->experience;
    }

    /**
     * @param int|null $experience
     */
    public function setExperience(?int $experience): void
    {
        $this->experience = $experience;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getNameSubject(): ?string
    {
        return $this->nameSubject;
    }

    /**
     * @param string|null $nameSubject
     */
    public function setNameSubject(?string $nameSubject): void
    {
        $this->nameSubject = $nameSubject;
    }

    /**
     * @return string|null
     */
    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    /**
     * @param string|null $speciality
     */
    public function setSpeciality(?string $speciality): void
    {
        $this->speciality = $speciality;
    }
}
