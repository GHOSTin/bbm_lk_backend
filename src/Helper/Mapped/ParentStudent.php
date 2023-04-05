<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class ParentStudent extends AbstractUser
{
    /** @var string|null
     */
    private $studentId;

    /** @var string|null
     */
    private $studentFullName;

    /** @var integer|null
     * @Groups({"show"})
     */
    private $gradeBookId;

    /** @var string|null
     * @Groups({"show"})
     */
    private $group;

    /** @var string|null
     * @Groups({"show"})
     */
    private $level;
    
    public function __construct()
    {
        $this->role = AbstractUserRole::ROLE_PARENT;
    }

    /**
     * @return string|null
     */
    public function getStudentId(): ?string
    {
        return $this->studentId;
    }

    /**
     * @param string|null $studentId
     */
    public function setStudentId(?string $studentId): void
    {
        $this->studentId = $studentId;
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
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * @param string|null $level
     */
    public function setLevel(?string $level): void
    {
        $this->level = $level;
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
     * @return string|null
     */
    public function getStudentFullName(): ?string
    {
        return $this->studentFullName;
    }

    /**
     * @param string|null $studentFullName
     */
    public function setStudentFullName(?string $studentFullName): void
    {
        $this->studentFullName = $studentFullName;
    }
}
