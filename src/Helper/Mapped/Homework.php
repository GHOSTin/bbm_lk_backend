<?php


namespace App\Helper\Mapped;


class Homework
{
    /** @var string */
    private $subjectName;

    /** @var string */
    private $student;

    /** @var string */
    private $group;

    /** @var \DateTime */
    private $date;

    /** @var string */
    private $description;

    /** @var array|string[] */
    private $attachments;

    /**
     * @return string
     */
    public function getSubjectName(): string
    {
        return $this->subjectName;
    }

    /**
     * @param string $subjectName
     */
    public function setSubjectName(string $subjectName): void
    {
        $this->subjectName = $subjectName;
    }

    /**
     * @return string
     */
    public function getStudent(): string
    {
        return $this->student;
    }

    /**
     * @param string $student
     */
    public function setStudent(string $student): void
    {
        $this->student = $student;
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
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array|string[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array|string[] $attachments
     */
    public function setAttachments($attachments): void
    {
        $this->attachments = $attachments;
    }
}