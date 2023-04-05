<?php


namespace App\Helper\Status;


class TokenExternalStatus extends AbstractStatus
{
    const ACTIVE = 1;
    const EXPIRED = 21;

    protected static $statusNames = [
        self::ACTIVE => 'Активен',
        self::EXPIRED => 'Истечен',
    ];
}