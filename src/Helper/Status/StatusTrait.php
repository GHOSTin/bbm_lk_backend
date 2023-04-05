<?php


namespace App\Helper\Status;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait StatusTrait
{
    /**
     * @var integer|null
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status = AbstractStatus::ACTIVE;

    /**
     * Set status
     *
     * @param integer $status
     * @return $this
     *
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     *
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return AbstractStatus
     */
    protected function getStatusClass()
    {
        $function = new \ReflectionClass($this);
        $statusClassName = 'App\Helper\Status\\' . $function->getShortName() . "Status";
        $statusClass = new $statusClassName;
        return $statusClass;
    }

    /**
     * @return mixed|string
     */
    public function getStatusType()
    {
        $statusClass = $this->getStatusClass();
        return $statusClass::getType($this->getStatus());
    }

    /**
     * Returns status name
     * @return string
     */
    public function getStatusName()
    {
        $statusClass = $this->getStatusClass();
        return $statusClass::getStatusName($this->getStatus());
    }
}