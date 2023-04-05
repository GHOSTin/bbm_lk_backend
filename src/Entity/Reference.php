<?php

namespace App\Entity;

use App\Helper\Status\ReferenceStatus;
use App\Helper\Status\StatusTrait;
use App\Repository\ReferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReferenceRepository::class)
 */
class Reference
{
    use StatusTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=UserReference::class, mappedBy="reference")
     */
    private $userReferences;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pathFile;

    public function __construct()
    {
        $this->userReferences = new ArrayCollection();
        $this->status = ReferenceStatus::ACTIVE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|UserReference[]
     */
    public function getUserReferences(): Collection
    {
        return $this->userReferences;
    }

    public function addUserReference(UserReference $userReference): self
    {
        if (!$this->userReferences->contains($userReference)) {
            $this->userReferences[] = $userReference;
            $userReference->setReference($this);
        }

        return $this;
    }

    public function removeUserReference(UserReference $userReference): self
    {
        if ($this->userReferences->contains($userReference)) {
            $this->userReferences->removeElement($userReference);
            // set the owning side to null (unless already changed)
            if ($userReference->getReference() === $this) {
                $userReference->setReference(null);
            }
        }

        return $this;
    }

    public function getPathFile(): ?string
    {
        return $this->pathFile;
    }

    public function setPathFile(?string $pathFile): self
    {
        $this->pathFile = $pathFile;

        return $this;
    }
}
