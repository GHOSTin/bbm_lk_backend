<?php

namespace App\Helper\Mapped;

use App\Helper\Role\AbstractUserRole;
use Symfony\Component\Serializer\Annotation\Groups;

class StudentList
{
    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $fullName;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $avatarImageUrl;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $externalGuid;

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
    public function getAvatarImageUrl(): ?string
    {
        return $this->avatarImageUrl;
    }

    /**
     * @param string|null $avatarImageUrl
     */
    public function setAvatarImageUrl(?string $avatarImageUrl): void
    {
        $this->avatarImageUrl = $avatarImageUrl;
    }

    /**
     * @return string|null
     */
    public function getExternalGuid(): ?string
    {
        return $this->externalGuid;
    }

    /**
     * @param string|null $externalGuid
     */
    public function setExternalGuid(?string $externalGuid): void
    {
        $this->externalGuid = $externalGuid;
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
