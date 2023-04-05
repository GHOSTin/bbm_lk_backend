<?php


namespace App\Serializer\Denormalize\Mapped;

use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\Lesson;
use App\Helper\Mapped\Schedule;
use App\Helper\Mapped\Teacher;
use App\Helper\Role\AbstractUserRole;
use App\Service\DateTimeService;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class ScheduleDenormalizer implements ContextAwareDenormalizerInterface
{
    use DenormalizeApiTrait;

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Schedule and $context['api'] ?? null == 'mapped';
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data Data to restore
     * @param string $type The expected class to instantiate
     * @param string $format Format the given data was extracted from
     * @param array $context Options available to the denormalizer
     *
     * @return object|array
     *
     * @throws BadMethodCallException   Occurs when the normalizer is not called in an expected context
     * @throws InvalidArgumentException Occurs when the arguments are not coherent or not supported
     * @throws UnexpectedValueException Occurs when the item cannot be hydrated with the given data
     * @throws ExtraAttributesException Occurs when the item doesn't have attribute to receive given data
     * @throws LogicException           Occurs when the normalizer is not supposed to denormalize
     * @throws RuntimeException         Occurs if the class cannot be instantiated
     * @throws ExceptionInterface       Occurs for all the other cases of errors
     */
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
        $lessons = [];
        foreach ($data as $lesson) {
            $lesson = $this->getSerializer()->denormalize($lesson, Lesson::class, $format, $context);
            $lessons[] = $lesson;
        }

        usort($lessons, function (Lesson $a, Lesson $b) {
            return $a->getStartLesson()->getTimestamp() - $b->getStartLesson()->getTimestamp();
        });

        $groupLessonsByDate = [];
        /** @var Lesson $lesson */
        foreach ($lessons as $lesson) {
            $groupLessonsByDate[DateTimeService::getDateTimeToIso8601InMidnight($lesson->getDate())][] = $lesson;
        }
        uksort($groupLessonsByDate, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        $schedules = [];
        foreach ($groupLessonsByDate as $keyDate => $lessonsArray) {
            $entityMapped = new Schedule();
            $entityMapped->setDate($lessonsArray[0]->getDate());
            $entityMapped->setWeek($lesson->getWeek());
            $entityMapped->setGroup($lesson->getGroup());
            $entityMapped->setLessons($lessonsArray);
            $schedules[] = $entityMapped;
        }
        return $schedules;
    }
}