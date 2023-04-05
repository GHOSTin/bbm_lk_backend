<?php


namespace App\Service;


use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Role\AbstractUserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScheduleService extends AbstractService
{
    protected $externalScheduleService;

    public function __construct(EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                SerializerInterface $serializer,
                                \App\Service\ApiExternal\ScheduleService $externalScheduleService
    )
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->externalScheduleService = $externalScheduleService;
    }

    public function getScheduleByDateAndGroup($startDateString, $endDateString, $group, $user) {
        try {
            $startDateTimestamp = DateTimeService::getDateTimeFromString($startDateString)
                ->modify('midnight')
                ->getTimestamp();
            $endDateTimestamp = DateTimeService::getDateTimeFromString($endDateString)
                ->modify('midnight')
                ->getTimestamp();
        }
        catch (\Exception $exception) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Invalid startDate or endDate parameters'
            );
        }
        switch (true) {
            case $user instanceof Student:
            case $user instanceof ParentStudent:
                if (is_null($group)) {
                    ApiExceptionHandler::errorApiHandlerMessage(
                        null,
                        'For Student and Parent required fields: group'
                    );
                }
                $schedules = $this->externalScheduleService->getScheduleByDateForStudentAndParent(
                    $startDateTimestamp,
                    $endDateTimestamp,
                    $group,
                    $user->getTokenExternal()->getToken()
                );
                break;
            case $user instanceof Teacher:
                $schedules = $this->externalScheduleService->getScheduleByDateForTeacher(
                    $startDateTimestamp,
                    $endDateTimestamp,
                    $user->getFirstName(),
                    $user->getLastName(),
                    $group,
                    $user->getTokenExternal()->getToken()
                );
                break;
            default:
                $schedules = [];
        }
        return $schedules;
    }
}