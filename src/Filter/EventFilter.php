<?php


namespace App\Filter;

class EventFilter extends AbstractFilter
{
    public function __construct()
    {
        parent::__construct();
        $this->pagination->setPage(1);
        $this->pagination->setPerPage(10);
    }

    public function postSerialiseAction()
    {
        // TODO: Implement postSerialiseAction() method.
    }
}