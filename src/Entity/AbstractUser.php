<?php

namespace App\Entity;

use App\Helper\Role\RoleTrait;
use App\Helper\Status\StatusTrait;
use App\Helper\Status\AbstractUserStatus;
use App\Repository\AbstractUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AbstractUserRepository::class)
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "1" = "Student",
 *     "2" = "ParentStudent",
 *     "3" = "Teacher",
 * })
 */
abstract class AbstractUser implements UserInterface
{
    use StatusTrait;
    use RoleTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"login_show"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"login_show"})
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity=Device::class, mappedBy="user")
     */
    protected $devices;

    /**
     * @ORM\OneToOne(targetEntity=TokenExternal::class, mappedBy="user", cascade={"persist", "remove"})
     */
    protected $tokenExternal;

    /**
     * @ORM\OneToMany(targetEntity=UserReference::class, mappedBy="user")
     */
    protected $userReferences;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatarImageUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"login_show"})
     */
    private $externalGuid;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     * @Groups({"login_show"})
     */
    private $group;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $myInterests = [];


    public function __construct()
    {
        $this->status = AbstractUserStatus::ACTIVE;
        $this->devices = new ArrayCollection();
        $this->userReferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
            $device->setUser($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->contains($device)) {
            $this->devices->removeElement($device);
            // set the owning side to null (unless already changed)
            if ($device->getUser() === $this) {
                $device->setUser(null);
            }
        }

        return $this;
    }

    public function getTokenExternal(): ?TokenExternal
    {
        return $this->tokenExternal;
    }

    public function setTokenExternal(TokenExternal $tokenExternal): self
    {
        $this->tokenExternal = $tokenExternal;

        // set the owning side of the relation if necessary
        if ($tokenExternal->getUser() !== $this) {
            $tokenExternal->setUser($this);
        }

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
            $userReference->setUser($this);
        }

        return $this;
    }

    public function removeUserReference(UserReference $userReference): self
    {
        if ($this->userReferences->contains($userReference)) {
            $this->userReferences->removeElement($userReference);
            // set the owning side to null (unless already changed)
            if ($userReference->getUser() === $this) {
                $userReference->setUser(null);
            }
        }

        return $this;
    }

    public function getExternalGuid(): ?string
    {
        return $this->externalGuid;
    }

    public function setExternalGuid(?string $externalGuid): self
    {
        $this->externalGuid = $externalGuid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatarImageUrl()
    {
        return $this->avatarImageUrl;
    }

    /**
     * @param mixed $avatarImageUrl
     */
    public function setAvatarImageUrl($avatarImageUrl): void
    {
        $this->avatarImageUrl = $avatarImageUrl;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
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

    public function getMyInterests(): ?array
    {
        return $this->myInterests;
    }

    public function setMyInterests(?array $myInterests): self
    {
        $this->myInterests = $myInterests;

        return $this;
    }
}
