<?php

namespace App\Entity;

use App\Helper\Status\DeviceStatus;
use App\Helper\Status\StatusTrait;
use App\Helper\Status\TokenExternalStatus;
use App\Repository\TokenExternalRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenExternalRepository::class)
 */
class TokenExternal
{
    use StatusTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredAt;

    /**
     * @ORM\OneToOne(targetEntity=AbstractUser::class, inversedBy="tokenExternal", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->expiredAt = new DateTime('+1 day');
        $this->status = TokenExternalStatus::ACTIVE;
    }

    public function refreshExpiredToken($token) {
        $this->token = $token;
        $this->expiredAt = new DateTime('+1 day');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getUser(): ?AbstractUser
    {
        return $this->user;
    }

    public function setUser(AbstractUser $user): self
    {
        $this->user = $user;

        return $this;
    }
}
