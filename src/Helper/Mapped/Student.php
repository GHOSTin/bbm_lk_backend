<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use DateTime;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class Student extends AbstractUser
{
    /** @var integer|null
     * @Groups({"show"})
     */
    private $gradeBookId;

    /** @var integer|null
     */
    private $bitrixId;

    /** @var string|null
     * @Groups({"show"})
     */
    private $sex;

    /** @var string|null
     * @Groups({"show"})
     */
    private $group;

    /** @var string|null
     * @Groups({"show"})
     */
    private $course;

    /** @var string|null
     * @Groups({"show"})
     */
    private $speciality;

    /** @var string|null
     * @Groups({"show"})
     */
    private $tutor;

    /**
     * @var UserInterests[]|null
     * @SWG\Property(property="interests", type="array", @SWG\Items(ref=@Model(type=UserInterests::class)))
     */
    private $interests;

    /** @var string|null
     * @Groups({"show"})
     */
    private $trainingPeriod;

    /** @var string|null
     * @Groups({"show"})
     */
    private $trainingStartYear;

    /** @var string|null
     * @Groups({"show"})
     */
    private $trainingEndYear;
    
    public function __construct()
    {
        $this->role = AbstractUserRole::ROLE_STUDENT;
    }

    /**
     * @return int|null
     */
    public function getGradeBookId(): ?int
    {
        return $this->gradeBookId;
    }

    /**
     * @param int|null $gradeBookId
     */
    public function setGradeBookId(?int $gradeBookId): void
    {
        $this->gradeBookId = $gradeBookId;
    }

    /**
     * @return int|null
     */
    public function getBitrixId(): ?int
    {
        return $this->bitrixId;
    }

    /**
     * @param int|null $bitrixId
     */
    public function setBitrixId(?int $bitrixId): void
    {
        $this->bitrixId = $bitrixId;
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
     * @return string|null
     */
    public function getCourse(): ?string
    {
        return $this->course;
    }

    /**
     * @param string|null $course
     */
    public function setCourse(?string $course): void
    {
        $this->course = $course;
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

    /**
     * @return string|null
     */
    public function getTutor(): ?string
    {
        return $this->tutor;
    }

    /**
     * @param string|null $tutor
     */
    public function setTutor(?string $tutor): void
    {
        $this->tutor = $tutor;
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
     * @return string|null
     */
    public function getTrainingPeriod(): ?string
    {
        return $this->trainingPeriod;
    }

    /**
     * @param string|null $trainingPeriod
     */
    public function setTrainingPeriod(?string $trainingPeriod): void
    {
        $this->trainingPeriod = $trainingPeriod;
    }

    /**
     * @return string|null
     */
    public function getTrainingStartYear(): ?string
    {
        return $this->trainingStartYear;
    }

    /**
     * @param string|null $trainingStartYear
     */
    public function setTrainingStartYear(?string $trainingStartYear): void
    {
        $this->trainingStartYear = $trainingStartYear;
    }

    /**
     * @return string|null
     */
    public function getTrainingEndYear(): ?string
    {
        return $this->trainingEndYear;
    }

    /**
     * @param string|null $trainingEndYear
     */
    public function setTrainingEndYear(?string $trainingEndYear): void
    {
        $this->trainingEndYear = $trainingEndYear;
    }
}
