<?php


namespace App\Serializer\Denormalize\Mapped;


use App\Helper\Mapped\Journal;
use App\Helper\Mapped\Period;
use App\Helper\Mapped\Point;
use App\Service\DateTimeService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class JournalDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $journals = [];

        foreach ($data as $datum) {
            /** @var Journal $entity */
            $entity = new $type();
            $entity->setDate($this->getCorrectTimeLesson($datum['startTime'] ?? null));
            $entity->setGroup($datum['group']);
            $entity->setLessonNumber($datum['lessonNumber']);
            $entity->setLessonType($datum['lessonType']);
            $entity->setSubject($datum['subject']);
            $entity->setSubjectExternalGuid($datum['subjectId']);
            $entity->setRoom($datum['room']);
            $entity->setTeacher($datum['teacher']);
            $entity->setTeacherExternalGuid($datum['teacher_id']);
            $entity->setWeekNumber((integer)$datum['weekNumber']);

            $period = new Period();
            $period->setStart($this->getCorrectTimeLesson($datum['startTime'] ?? null));
            $period->setEnd($this->getCorrectTimeLesson($datum['endTime'] ?? null));

            $entity->setPeriod($period);
            foreach ($datum['points'] as $point) {
                $comment = $point['comment'] ?? null;
                if ($comment)
                    $entity->setCountComments($entity->getCountComments() + 1);
                $entity->addPoint(
                    new Point(
                        (integer)$point['point'],
                        (integer)$point['pointNumber'],
                        $comment)
                );
            }

            array_push($journals, $entity);
        }
        return $journals;
    }

    public function getCorrectTimeLesson($time) {
        if ($time) {
            $dateTime = new DateTime($time, new \DateTimeZone('Asia/Yekaterinburg'));
//            $dateTime->setDate($currentDate->format('Y'), $currentDate->format('n'), $currentDate->format('j'));
            return $dateTime->setTimezone(new \DateTimeZone('UTC'));
        }
        return null;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Journal;
    }
}