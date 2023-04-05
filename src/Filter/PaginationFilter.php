<?php


namespace App\Filter;

class PaginationFilter
{
    /** @var string|null */
    protected $page;

    /**
     * @var string|null
     */
    protected $per_page;

    public function __construct()
    {
        $this->page = 1;
        $this->per_page = 10;
    }

    /**
     * @return string|null
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param string|null $page
     */
    public function setPage(?string $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string|null
     */
    public function getPerPage(): ?string
    {
        return $this->per_page;
    }

    /**
     * @param string|null $per_page
     */
    public function setPerPage(?string $per_page): void
    {
        $this->per_page = $per_page;
    }
}