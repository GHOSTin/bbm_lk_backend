<?php


namespace App\Helper\Mapped;


use DateTime;

class Debt
{

    /** @var string|null */
    private $doc;

    /** @var DateTime */
    private $date;

    /** @var string|null */
    private $period;

    /** @var string|null */
    private $control;

    /** @var string|null */
    private $subject;

    /** @var string|null */
    private $subjectId;

    /** @var string|null */
    private $teacher;

    /** @var string|null */
    private $teacher_id;

    /** @var string|null */
    protected $teacherAvatar;

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string|null
     */
    public function getDoc(): ?string
    {
        return $this->doc;
    }
    /**
     * @param string $doc
     */
    public function setDoc(string $doc): void
    {
        $this->doc = $doc;
    }

    /**
     * @return string|null
     */
    public function getPeriod(): ?string
    {
        return $this->doc;
    }
    /**
     * @param string $period
     */
    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    /**
     * @return string|null
     */
    public function getControl(): ?string
    {
        return $this->control;
    }
    /**
     * @param string $control
     */
    public function setControl(string $control): void
    {
        $this->control = $control;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
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
     * @return string|null
     */
    public function getSubjectId(): ?string
    {
        return $this->subjectId;
    }
    /**
     * @param string $subjectId
     */
    public function setSubjectId(string $subjectId): void
    {
        $this->subjectId = $subjectId;
    }

    /**
     * @return string|null
     */
    public function getTeacher(): ?string
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
     * @return string|null
     */
    public function getTeacherId(): ?string
    {
        return $this->teacher_id;
    }
    /**
     * @param string $teacher_id
     */
    public function setTeacherId(string $teacher_id): void
    {
        $this->teacher_id = $teacher_id;
    }

    /**
     * @return string
     */
    public function getTeacherAvatar(): ?string
    {
        return $this->teacherAvatar;
    }
    /**
     * @param string $teacherAvatar
     */
    public function setTeacherAvatar(?string $teacherAvatar): void
    {
        $this->teacherAvatar = $teacherAvatar;
    }
}