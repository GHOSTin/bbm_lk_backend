<?php


namespace App\Helper\Status;


class DeviceStatus extends AbstractStatus
{
    const ACTIVE = 1;
    const EXPIRED = 21;
    const INACTIVE = 22;

    protected static $statusNames = [
        self::ACTIVE => 'Активен',
        self::EXPIRED => 'Истечен',
        self::INACTIVE => 'Неактивен',
    ];
}