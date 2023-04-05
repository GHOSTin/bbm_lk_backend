<?php

namespace App\Entity;

use App\Helper\Role\AbstractUserRole;
use App\Repository\ParentStudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParentStudentRepository::class)
 */
class ParentStudent extends AbstractUser
{
    const TYPE = AbstractUserRole::ROLE_PARENT;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $gradeBookId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $surname;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $studentExternalId;

    public function __construct()
    {
        parent::__construct();
        $this->role = AbstractUserRole::ROLE_PARENT;
    }

    public function getGradeBookId(): ?int
    {
        return $this->gradeBookId;
    }

    public function setGradeBookId(int $gradeBookId): self
    {
        $this->gradeBookId = $gradeBookId;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getType(): int
    {
        return self::TYPE;
    }

    /**
     * @return string|null
     */
    public function getStudentExternalId(): ?string
    {
        return $this->studentExternalId;
    }

    /**
     * @param string|null $studentExternalId
     */
    public function setStudentExternalId(?string $studentExternalId): void
    {
        $this->studentExternalId = $studentExternalId;
    }
}
