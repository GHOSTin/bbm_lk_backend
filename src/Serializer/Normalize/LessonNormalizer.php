<?php


namespace App\Serializer\Normalize;


use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\AbstractUser;
use App\Helper\Mapped\Lesson;
use App\Helper\Mapped\ParentStudent;
use App\Helper\Mapped\Schedule;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\Teacher;
use App\Helper\Role\AbstractUserRole;
use App\Service\DateTimeService;
use App\Service\ProfileService;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class LessonNormalizer implements ContextAwareNormalizerInterface
{
    use DenormalizeApiTrait;

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param mixed $object Object to normalize
     * @param string $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool|\ArrayObject|null \ArrayObject is used to make sure an empty object is encoded as an object not an array
     *
     * @throws InvalidArgumentException   Occurs when the object given is not a supported type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     * @throws ExceptionInterface         Occurs for all the other cases of errors
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        /** @var Lesson $object */
        $data = [
            'startLesson' => DateTimeService::getDateTimeToIso8601($object->getStartLesson()),
            'endLesson' => DateTimeService::getDateTimeToIso8601($object->getEndLesson()),
            'lessonNumber' => $object->getLessonNumber(),
            'lessonType' => $object->getLessonType(),
            'group' => $object->getGroup(),
            'subgroup' => $object->getSubgroup(),
            'subject' => $object->getSubject(),
            'subjectFull' => $object->getSubjectFull(),
            'campus' => $object->getCampus(),
            'room' => $object->getRoom(),
            'teacher' => $object->getTeacher(),
            'teacherExternalGuid' => $object->getTeacherExternalGuid(),
            'week' => $object->getWeek(),
        ];
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Lesson and $context['api'] ?? null == 'internal';
    }
}