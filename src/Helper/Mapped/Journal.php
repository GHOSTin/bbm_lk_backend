<?php


namespace App\Helper\Mapped;


use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class Journal
{
    /** @var \DateTime */
    private $date;

    /** @var string */
    private $group;

    /** @var int */
    private $lessonNumber;

    /** @var string */
    private $lessonType;

    /** @var string */
    private $subject;

    /** @var string */
    private $subjectExternalGuid;

    /** @var string */
    private $teacher;

    /** @var string */
    private $teacherExternalGuid;

    /** @var string */
    private $room;

    /** @var int */
    private $weekNumber;

    /** @var string|null */
    private $homework;

    /** @var Period */
    private $period;

    /** @var int */
    private $countComments;

    /**
     * @var array|Point[]
     * @SWG\Property(property="points", type="array", @SWG\Items(ref=@Model(type=Point::class)))
     */
    private $points;

    public function __construct()
    {
        $this->points = [];
        $this->countComments = 0;
    }

    /**
     * @return string
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    /**
     * @return int
     */
    public function getLessonNumber(): int
    {
        return $this->lessonNumber;
    }

    /**
     * @param int $lessonNumber
     */
    public function setLessonNumber(int $lessonNumber): void
    {
        $this->lessonNumber = $lessonNumber;
    }

    /**
     * @return string
     */
    public function getLessonType(): string
    {
        return $this->lessonType;
    }

    /**
     * @param string $lessonType
     */
    public function setLessonType(string $lessonType): void
    {
        $this->lessonType = $lessonType;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getTeacher(): string
    {
        return $this->teacher;
    }

    /**
     * @param string $teacher
     */
    public function setTeacher(string $teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @param string $room
     */
    public function setRoom(string $room): void
    {
        $this->room = $room;
    }

    /**
     * @return int
     */
    public function getWeekNumber(): int
    {
        return $this->weekNumber;
    }

    /**
     * @param int $weekNumber
     */
    public function setWeekNumber(int $weekNumber): void
    {
        $this->weekNumber = $weekNumber;
    }

    /**
     * @return Point[]|array
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param Point[]|array $points
     */
    public function setPoints(array $points): void
    {
        $this->points = $points;
    }

    /**
     * @param Point $point
     */
    public function  addPoint(Point $point): void
    {
        array_push($this->points, $point);
    }

    /**
     * @return string
     */
    public function getSubjectExternalGuid(): string
    {
        return $this->subjectExternalGuid;
    }

    /**
     * @param string $subjectExternalGuid
     */
    public function setSubjectExternalGuid(string $subjectExternalGuid): void
    {
        $this->subjectExternalGuid = $subjectExternalGuid;
    }

    /**
     * @return string|null
     */
    public function getHomework(): ?string
    {
        return $this->homework;
    }

    /**
     * @param string|null $homework
     */
    public function setHomework(?string $homework): void
    {
        $this->homework = $homework;
    }

    /**
     * @return string
     */
    public function getTeacherExternalGuid(): string
    {
        return $this->teacherExternalGuid;
    }

    /**
     * @param string $teacherExternalGuid
     */
    public function setTeacherExternalGuid(string $teacherExternalGuid): void
    {
        $this->teacherExternalGuid = $teacherExternalGuid;
    }

    /**
     * @return Period
     */
    public function getPeriod(): Period
    {
        return $this->period;
    }

    /**
     * @param Period $period
     */
    public function setPeriod(Period $period): void
    {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getCountComments(): int
    {
        return $this->countComments;
    }

    /**
     * @param int $countComments
     */
    public function setCountComments(int $countComments): void
    {
        $this->countComments = $countComments;
    }
}