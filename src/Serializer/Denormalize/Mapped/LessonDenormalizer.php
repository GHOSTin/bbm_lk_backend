<?php


namespace App\Serializer\Denormalize\Mapped;

use App\Helper\Mapped\Lesson;
use App\Service\DateTimeService;
use DateTime;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class LessonDenormalizer implements ContextAwareDenormalizerInterface
{
    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Lesson and $context['api'] ?? null == 'mapped';
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $entityMapped = new Lesson();
        $entityMapped->setDate($this->getCorrectTimeLesson($data['startTime'] ?? null));
        $entityMapped->setStartLesson($this->getCorrectTimeLesson($data['startTime'] ?? null));
        $entityMapped->setEndLesson($this->getCorrectTimeLesson($data['endTime'] ?? null));
        $entityMapped->setGroup($data['group'] ?? null);
        $entityMapped->setLessonNumber($data['lessonNumber'] ?? null);
        $entityMapped->setLessonType($data['lessonType'] ?? null);
        $entityMapped->setSubgroup($data['subgroup'] ?? null);
        $entityMapped->setSubject($data['subject'] ?? null);
        $entityMapped->setSubjectFull($data['subjectFull'] ?? null);
        $entityMapped->setCampus($data['campus'] ?? null);
        $entityMapped->setRoom($data['room'] ?? null);
        $entityMapped->setTeacher($data['teacher'] ?? null);
        $entityMapped->setWeek($data['weekNumber'] ?? null);
        $entityMapped->setTeacherExternalGuid($data['teacher_id'] ?? null);
        return $entityMapped;
    }

    public function getCorrectTimeLesson($time) {
        if ($time) {
            $dateTime = new DateTime($time, new \DateTimeZone('Asia/Yekaterinburg'));
            return $dateTime->setTimezone(new \DateTimeZone('UTC'));
        }
        return null;
    }
}