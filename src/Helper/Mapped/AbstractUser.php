<?php

namespace App\Helper\Mapped;


use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractUser
{
    /** @var integer|null
     * @Groups({"show"})
     */
    protected $id;

    /** @var string|null
     * @Groups({"show"})
     */
    protected $email;

    /** @var integer|null
     * @Groups({"show"})
     */
    protected $role;

    /** @var string|null
     * @Groups({"show"})
     */
    protected $avatarImageUrl;

    /** @var string|null
     * @Groups({"show"})
     */
    protected $name;

    /** @var string|null
     * @Groups({"show"})
     */
    private $externalGuid;

    /** @var string|null
     * @Groups({"show"})
     */
    protected $phone;

    /** @var \DateTimeInterface|null
     * @Groups({"show"})
     */
    private $birthday;

    /** @var integer|null
     * @Groups({"show"})
     */
    private $age;

    /** @var string|null
     * @Groups({"show"})
     */
    protected $city;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int|null
     */
    public function getRole(): ?int
    {
        return $this->role;
    }

    /**
     * @param int|null $role
     */
    public function setRole(?int $role): void
    {
        $this->role = $role;
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * @param \DateTimeInterface|null $birthday
     */
    public function setBirthday(?\DateTimeInterface $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int|null $age
     */
    public function setAge(?int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }
}
