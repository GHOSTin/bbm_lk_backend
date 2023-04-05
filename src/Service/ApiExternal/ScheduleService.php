<?php


namespace App\Service\ApiExternal;



use App\Entity\AbstractUser;
use App\Entity\TokenExternal;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Mapped\Lesson;
use App\Helper\Mapped\Schedule;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ScheduleService extends AbstractService
{
    private const method_schedules = '/schedules/';

    public function getScheduleByDate($methodUrl, $token)
    {
        $response = $this->makeGetRequest($methodUrl, null, null, $token);
        $dataJson = $this->getContent($response);
        return $this->serializer->deserialize(
            $dataJson,
            Schedule::class,
            'json',
            [
                'api' => 'mapped',
                'group' => 'denormalizeIndex',
            ]
        );
    }

    public function getScheduleByDateForTeacher(
        $startDateTimestamp,
        $endDateTimestamp,
        $firstName,
        $lastName,
        $groupName,
        $token
    )
    {
        $url = self::method_schedules . (string)$startDateTimestamp . '/' . (string)$endDateTimestamp .
            '/?firstName=' . $firstName . '&lastName=' . $lastName;
        if (!is_null($groupName)) {
            $url .= '&group=' . $groupName;
        }
        return $this->getScheduleByDate($url, $token);
    }

    public function getScheduleByDateForStudentAndParent(
        $startDateTimestamp,
        $endDateTimestamp,
        $groupName,
        $token
    )
    {
        $url = self::method_schedules . (string)$startDateTimestamp . '/' .
            (string)$endDateTimestamp . '/?group=' . $groupName;
        return $this->getScheduleByDate($url, $token);
    }
}