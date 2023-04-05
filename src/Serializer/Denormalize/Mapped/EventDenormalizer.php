<?php

namespace App\Serializer\Denormalize\Mapped;

use App\Entity\Teacher;
use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\Event;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\Subject;
use App\Helper\Mapped\TeacherList;
use App\Service\ApiExternal\ProfileService;
use App\Service\DateTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class EventDenormalizer implements ContextAwareDenormalizerInterface
{
    use DenormalizeApiTrait;

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Event and $context['api'] ?? null == 'mapped';
    }

    public function denormalize($data, $type, string $format = null, array $context = [])
    {
        if (array_key_exists('group', $context) and method_exists($this, $context['group'])) {
            $denormalize = $context['group'];
            return $this->$denormalize($data, $type, $format, $context);
        }
        return null;
    }

    public function denormalizeIndex($data, string $type, string $format = null, array $context = [])
    {
        $mappedEvents = [];
        foreach ($data as $datum) {
            $mappedEvent = $this->denormalizeShow($datum, $type, $format, $context);
            $mappedEvents[] = $mappedEvent;
        }
        uasort($mappedEvents, function (Event $a, Event $b) {
            return $a->getDate() > $b->getDate();
        });
        return $mappedEvents;
    }

    public function denormalizeShow($data, string $type, string $format = null, array $context = [])
    {
        /** @var Event $entity */
        $entity = new $type();
        $entity->setId($data['id']);
        $entity->setName($data['name'] ?? null);
        $entity->setFullName($data['fullName'] ?? null);
        $entity->setDescription($data['description'] ?? null);
        $entity->setType($data['type'] ?? null);
        $entity->setPeriod($data['period'] ?? null);
        $entity->setDate(
            array_key_exists('date', $data) ?
                DateTimeService::getDateTimeFromString($data['date']) :
                null);

        return $entity;
    }
}