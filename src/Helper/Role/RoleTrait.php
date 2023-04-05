<?php


namespace App\Helper\Role;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait RoleTrait
{
    /**
     * @ORM\Column(type="integer")
     * @Groups({"login_show"})
     */
    protected $role;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * @return AbstractUserRole
     * @throws \ReflectionException
     */
    protected function getRoleClass()
    {
        $function = new \ReflectionClass($this);
        $statusClassName = 'App\Helper\Role\\' . $function->getShortName() . "Role";
        $statusClass = new $statusClassName;
        return $statusClass;
    }

    /**
     * Returns role name
     * @SerializedName("roleName")
     * @Groups({"login_show"})
     * @return string
     * @throws \ReflectionException
     */
    public function getRoleName()
    {
        $roleClass = $this->getRoleClass();
        return $roleClass::getRoleName($this->getRole());
    }

    /**
     * Returns role name
     * @return string
     * @throws \ReflectionException
     */
    public function getRoleNameForExternalApi()
    {
        $roleClass = $this->getRoleClass();
        return $roleClass::getRoleNameForApiExternal($this->getRole());
    }
}