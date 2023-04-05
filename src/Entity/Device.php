<?php

namespace App\Entity;

use App\Helper\Status\DeviceStatus;
use App\Helper\Status\StatusTrait;
use App\Repository\DeviceRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 */
class Device
{
    use StatusTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"login_show"})
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity=AbstractUser::class, inversedBy="devices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"login_show"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredAt;

    public function __construct()
    {
        $this->token = (md5(microtime().rand(100000, 10000000)));
        $this->status = DeviceStatus::ACTIVE;
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

    public function getUser(): ?AbstractUser
    {
        return $this->user;
    }

    public function setUser(?AbstractUser $user): self
    {
        $this->user = $user;

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
}
