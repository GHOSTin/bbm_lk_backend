<?php


namespace App\Helper\Status;


class AbstractUserStatus extends AbstractStatus
{
    const ACTIVE = 1;
    const BLOCKED = 21;

    protected static $statusNames = [
        self::ACTIVE => 'Активен',
        self::BLOCKED => 'Заблокирован',
    ];
}