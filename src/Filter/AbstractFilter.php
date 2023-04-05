<?php


namespace App\Filter;


use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractFilter
{
    /** @var string|null */
    protected $dateStart;

    /** @var string|null */
    protected $dateEnd;

    protected $default;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var PaginationFilter */
    protected $pagination;

    public function __construct()
    {
        $this->pagination = new PaginationFilter();
    }

    /**
     * @return string|null
     */
    public function getDateStart(): ?string
    {
        return $this->dateStart;
    }

    /**
     * @param string|null $dateStart
     */
    public function setDateStart(?string $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return string|null
     */
    public function getDateEnd(): ?string
    {
        return $this->dateEnd;
    }

    /**
     * @param string|null $dateEnd
     */
    public function setDateEnd(?string $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    public function getDefault()
    {
        if (!$this->default) {
            $this->default = new static();
        }
        return $this->default;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return PaginationFilter
     */
    public function getPagination(): PaginationFilter
    {
        return $this->pagination;
    }

    /**
     * @param PaginationFilter $pagination
     */
    public function setPagination(PaginationFilter $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function postSerialiseAction()
    {
        // TODO: Implement postSerialiseAction() method.
    }
}