<?php


namespace App\Helper\Status;


class LogImportStatus extends AbstractStatus
{
    const COMPLETED = 1;
    const ERROR = 21;

    protected static $statusNames = [
        self::COMPLETED => 'Выполнен',
        self::ERROR => 'Ошибка',
    ];
}