<?php


namespace App\Service;

use App\Entity\ParentStudent;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Helper\Mapped as Mapped;
use App\Entity\AbstractUser;
use App\Filter\EventFilter;
use App\Helper\Exception\ApiExceptionHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventService extends \App\Service\ApiExternal\AbstractService
{
    const EVENTS_ROUTE = '/events/';

    public function __construct(
        $apiExternalDomain,
        $apiVersion,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        parent::__construct($apiExternalDomain, $apiVersion, $entityManager, $validator, $serializer);
    }

    public function getEvents(AbstractUser $user, EventFilter $filter)
    {
        try {
            $startDateTime = $filter->getDateStart() ?
                DateTimeService::getDateTimeFromString($filter->getDateStart()) :
                new \DateTime('now midnight');
            $endDateTime = clone $startDateTime;
            $endDateTime = $endDateTime->modify('+1 year midnight');
            $startDateTimestamp = $startDateTime->getTimestamp();
            $endDateTimestamp = $endDateTime->getTimestamp();
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
                $url = self::EVENTS_ROUTE . '?user=' . $user->getGradeBookId() . '&startDate=' .
                    $startDateTimestamp . '&endDate=' . $endDateTimestamp;
                break;
            case $user instanceof Teacher:
                $url = self::EVENTS_ROUTE . '?firstName=' . $user->getFirstName() . '&lastName=' . $user->getLastName() .
                    '&startDate=' . $startDateTimestamp . '&endDate=' . $endDateTimestamp;
                break;
        }

        $this->setToken($user->getTokenExternal()->getToken());
        $dataEvents = $this->getContent($this->makeGetRequest($url, null, null));
        $events = $this->serializer->deserialize(
            $dataEvents,
            Mapped\Event::class,
            "json",
            [
                'api' => 'mapped',
                'group' => 'denormalizeIndex',
            ]
        );

        return array_slice(
            $events,
            ($filter->getPagination()->getPage() - 1) * $filter->getPagination()->getPerPage(),
            $filter->getPagination()->getPerPage()
        );
    }

    public function getEventById($eventId, $token)
    {
        $url = self::EVENTS_ROUTE . $eventId;

        $this->setToken($token);
        $dataEvent = $this->getContent($this->makeGetRequest($url, null, null));
        $event = $this->serializer->deserialize(
            $dataEvent,
            Mapped\Event::class,
            "json",
            [
                'api' => 'mapped',
                'group' => 'denormalizeShow',
            ]
        );

        return $event;
    }
}