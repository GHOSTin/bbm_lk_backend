<?php


namespace App\Serializer\Normalize;


use App\Entity\Teacher;
use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\Schedule;
use App\Service\DateTimeService;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ScheduleNormalizer implements ContextAwareNormalizerInterface
{
    use DenormalizeApiTrait;

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param mixed $object Object to normalize
     * @param string|null $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool|\ArrayObject|null \ArrayObject is used to make sure an empty object is encoded as an object not an array
     *
     * @throws ExceptionInterface Occurs for all the other cases of errors
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $user = $context['user'] ?? null;
        /** @var Schedule $object */
        $data = [
            'date' => DateTimeService::getDateTimeToIso8601($object->getDate()),
            'week' => $object->getWeek(),
            'group' => $user instanceof Teacher ? null : $object->getGroup()
        ];
        $lessons = [];
        foreach ($object->getLessons() as $lesson) {
            $lessons[] = $this->getSerializer()->normalize($lesson, null, $context);
        }
        $data['lessons'] = $lessons;
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Schedule and $context['api'] ?? null == 'internal';
    }
}