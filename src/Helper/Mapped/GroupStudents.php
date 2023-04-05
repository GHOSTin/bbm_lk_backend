<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class GroupStudents
{
    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $group;

    /**
     * @var string|null
     */
    protected $groupId;

    /**
     * @var array|StudentList[]|null
     * @Groups({"show"})
     * @SWG\Property(property="students", type="array", @SWG\Items(ref=@Model(type=StudentList::class)))
     */
    protected $students;

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

    /**
     * @return StudentList[]|array|null
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param StudentList[]|array|null $students
     */
    public function setStudents($students): void
    {
        $this->students = $students;
    }
}
