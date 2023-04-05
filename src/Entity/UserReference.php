<?php

namespace App\Entity;

use App\Repository\UserReferenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserReferenceRepository::class)
 */
class UserReference
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reference::class, inversedBy="userReferences")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show"})
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity=AbstractUser::class, inversedBy="userReferences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"show"})
     */
    private $note;

    /**
     * @ORM\Column(type="date")
     * @Groups({"show"})
     */
    private $dateOnReference;

    /**
     * @ORM\Column(type="date")
     * @Groups({"show"})
     */
    private $receiveDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?Reference
    {
        return $this->reference;
    }

    public function setReference(?Reference $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getUser(): ?AbstractUser
    {
        return $this->user;
    }

    public function setUser(?AbstractUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDateOnReference(): ?\DateTimeInterface
    {
        return $this->dateOnReference;
    }

    public function setDateOnReference(\DateTimeInterface $dateOnReference): self
    {
        $this->dateOnReference = $dateOnReference;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getReceiveDate(): ?\DateTimeInterface
    {
        return $this->receiveDate;
    }

    public function setReceiveDate(\DateTimeInterface $receiveDate): self
    {
        $this->receiveDate = $receiveDate;

        return $this;
    }
}
