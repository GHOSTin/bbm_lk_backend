<?php

namespace App\Entity;

use App\Helper\Role\AbstractUserRole;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student extends AbstractUser
{
    const TYPE = AbstractUserRole::ROLE_STUDENT;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $gradeBookId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $surname;
    
    public function __construct()
    {
        parent::__construct();
        $this->role = AbstractUserRole::ROLE_STUDENT;
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

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getType(): int
    {
        return self::TYPE;
    }
}
