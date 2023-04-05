<?php


namespace App\Service;


use DateTime;
use Exception;

class DateTimeService
{
    /**
     * @param string $dateTimeString
     * @return DateTime|null
     * @throws Exception
     */
    static function getDateTimeFromString(string $dateTimeString) {
        if ($dateTimeString) {
            return new DateTime($dateTimeString, new \DateTimeZone('UTC'));
        }
        return null;
    }

    /**
     * @param $dateTime
     * @return string|null
     */
    static function getDateTimeToIso8601($dateTime) {
        if ($dateTime) {
            return $dateTime->format(DateTime::ATOM);
        }
        return null;
    }

    /**
     * @param $dateTime
     * @return string|null
     */
    static function getDateTimeToIso8601InMidnight($dateTime) {
        if ($dateTime) {
            $dateTimeInMidnight = clone $dateTime;
            return $dateTimeInMidnight->modify('midnight')->format(DateTime::ATOM);
        }
        return null;
    }
}