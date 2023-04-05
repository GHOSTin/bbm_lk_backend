<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class Lesson
{
    /** @var \DateTimeInterface|null
     */
    private $date;

    /** @var \DateTimeInterface|null
     * @Groups({"show"})
     */
    private $startLesson;

    /** @var \DateTimeInterface|null
     * @Groups({"show"})
     */
    private $endLesson;

    /** @var string|null
     * @Groups({"show"})
     */
    private $group;

    /** @var string|null
     * @Groups({"show"})
     */
    private $lessonNumber;

    /** @var string|null
     * @Groups({"show"})
     */
    private $lessonType;

    /** @var string|null
     * @Groups({"show"})
     */
    private $subgroup;

    /** @var string|null
     * @Groups({"show"})
     */
    private $subject;

    /** @var string|null
     * @Groups({"show"})
     */
    private $subjectFull;

    /** @var string|null
     * @Groups({"show"})
     */
    private $campus;

    /** @var string|null
     * @Groups({"show"})
     */
    private $room;

    /** @var string|null
     * @Groups({"show"})
     */
    private $teacher;

    /** @var string|null
     * @Groups({"show"})
     */
    private $teacherExternalGuid;

    /** @var integer|null
     * @Groups({"show"})
     */
    private $week;

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartLesson(): ?\DateTimeInterface
    {
        return $this->startLesson;
    }

    /**
     * @param \DateTimeInterface|null $startLesson
     */
    public function setStartLesson(?\DateTimeInterface $startLesson): void
    {
        $this->startLesson = $startLesson;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndLesson(): ?\DateTimeInterface
    {
        return $this->endLesson;
    }

    /**
     * @param \DateTimeInterface|null $endLesson
     */
    public function setEndLesson(?\DateTimeInterface $endLesson): void
    {
        $this->endLesson = $endLesson;
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
    public function getLessonNumber(): ?string
    {
        return $this->lessonNumber;
    }

    /**
     * @param string|null $lessonNumber
     */
    public function setLessonNumber(?string $lessonNumber): void
    {
        $this->lessonNumber = $lessonNumber;
    }

    /**
     * @return string|null
     */
    public function getLessonType(): ?string
    {
        return $this->lessonType;
    }

    /**
     * @param string|null $lessonType
     */
    public function setLessonType(?string $lessonType): void
    {
        $this->lessonType = $lessonType;
    }

    /**
     * @return string|null
     */
    public function getSubgroup(): ?string
    {
        return $this->subgroup;
    }

    /**
     * @param string|null $subgroup
     */
    public function setSubgroup(?string $subgroup): void
    {
        $this->subgroup = $subgroup;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string|null
     */
    public function getSubjectFull(): ?string
    {
        return $this->subjectFull;
    }

    /**
     * @param string|null $subjectFull
     */
    public function setSubjectFull(?string $subjectFull): void
    {
        $this->subjectFull = $subjectFull;
    }

    /**
     * @return string|null
     */
    public function getCampus(): ?string
    {
        return $this->campus;
    }

    /**
     * @param string|null $campus
     */
    public function setCampus(?string $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string|null
     */
    public function getRoom(): ?string
    {
        return $this->room;
    }

    /**
     * @param string|null $room
     */
    public function setRoom(?string $room): void
    {
        $this->room = $room;
    }

    /**
     * @return string|null
     */
    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    /**
     * @param string|null $teacher
     */
    public function setTeacher(?string $teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return string|null
     */
    public function getTeacherExternalGuid(): ?string
    {
        return $this->teacherExternalGuid;
    }

    /**
     * @param string|null $teacherExternalGuid
     */
    public function setTeacherExternalGuid(?string $teacherExternalGuid): void
    {
        $this->teacherExternalGuid = $teacherExternalGuid;
    }

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
}
