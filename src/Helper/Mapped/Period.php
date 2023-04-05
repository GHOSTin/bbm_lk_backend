<?php


namespace App\Helper\Mapped;


class Period
{
    /** @var \DateTime */
    private $start;

    /** @var \DateTime */
    private $end;

    public function __construct()
    {
        $this->start = new \DateTime('2000-01-01');
        $this->end  = new \DateTime('2000-01-01');
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start): void
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd(\DateTime $end): void
    {
        $this->end = $end;
    }
}