<?php


namespace App\Helper\Exception;


use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Response;

class ResponseCode extends Response
{
    const HTTP_OBJECT_ALREADY_EXIST = 461;
    const HTTP_EXTERNAL_API_ERROR = 462;
    const HTTP_VALIDATION_ERROR = 463;

    private static $defaultMessageErrorByCode = [
        self::HTTP_BAD_REQUEST => 'Invalid Request',
        self::HTTP_FORBIDDEN => 'Forbidden Access',
        self::HTTP_UNAUTHORIZED => 'Authentication Required',
        self::HTTP_GONE => 'Object Already Deleted',
        self::HTTP_NOT_FOUND => 'Object Not Found',
        self::HTTP_OBJECT_ALREADY_EXIST => 'Object Already Exist',
        self::HTTP_EXTERNAL_API_ERROR => 'External Api Error',
        self::HTTP_VALIDATION_ERROR => 'Validation Error',
    ];

    public static function getDefaultMessageErrorByCode($key)
    {
        if (isset(static::$defaultMessageErrorByCode)) {
            if (array_key_exists($key, static::$defaultMessageErrorByCode)) {
                return static::$defaultMessageErrorByCode[$key];
            }
        }
        return 'Unhandled Error';
    }

}