<?php


namespace App\Helper\Mapped;


use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class Subject
{
    /**
     * @var string
     * @Groups({"show"})
     */
    protected $name;

    /**
     * @var string
     * @Groups({"show"})
     */
    protected $semester;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $control;

    /**
     * @var string
     * @Groups({"show"})
     */
    protected $teacherName;

    /**
     * @var string
     * @Groups({"show"})
     */
    protected $teacherAvatar;

    /**
     * @var string
     * @Groups({"show"})
     */
    protected $teacherExternalId;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $literature;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $group;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $groupId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSemester(): string
    {
        return $this->semester;
    }

    /**
     * @param string $semester
     */
    public function setSemester(string $semester): void
    {
        $this->semester = $semester;
    }

    /**
     * @return string|null
     */
    public function getControl(): ?string
    {
        return $this->control;
    }

    /**
     * @param string|null $control
     */
    public function setControl(?string $control): void
    {
        $this->control = $control;
    }

    /**
     * @return string
     */
    public function getTeacherName(): string
    {
        return $this->teacherName;
    }

    /**
     * @param string $teacherName
     */
    public function setTeacherName(string $teacherName): void
    {
        $this->teacherName = $teacherName;
    }

    /**
     * @return string
     */
    public function getTeacherExternalId(): string
    {
        return $this->teacherExternalId;
    }

    /**
     * @param string $teacherExternalId
     */
    public function setTeacherExternalId(string $teacherExternalId): void
    {
        $this->teacherExternalId = $teacherExternalId;
    }

    /**
     * @return string|null
     */
    public function getLiterature(): ?string
    {
        return $this->literature;
    }

    /**
     * @param string|null $literature
     */
    public function setLiterature(?string $literature): void
    {
        $this->literature = $literature;
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
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @param string|null $groupId
     */
    public function setGroupId(?string $groupId): void
    {
        $this->groupId = $groupId;
    }
}