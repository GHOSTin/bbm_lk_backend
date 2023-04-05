<?php


namespace App\Helper\Status;

abstract class AbstractStatus
{
    const INACTIVE = 11;
    const ACTIVE = 1;

    protected static $statusNames = [
        self::ACTIVE => 'Активен',
        self::INACTIVE => 'Не активен',
    ];

    protected static $statusTypes = [
        self::ACTIVE => 'primary',
        self::INACTIVE => 'warning',
    ];

    public static function getStatusName($key)
    {
        if (isset(static::$statusNames)) {
            if (array_key_exists($key, static::$statusNames)) {
                return static::$statusNames[$key];
            }
        }

        return '';
    }

    public static function getType($key)
    {
        if (isset(static::$statusTypes)) {
            if (array_key_exists($key, static::$statusTypes)) {
                return static::$statusTypes[$key];
            }
        }

        return '';
    }

    public static function getStatusNames()
    {
        return static::$statusNames;
    }
}