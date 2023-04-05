<?php

namespace App\Serializer\Denormalize\Mapped;

use App\Entity\Teacher;
use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\Event;
use App\Helper\Mapped\News;
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

class NewsDenormalizer implements ContextAwareDenormalizerInterface
{
    use DenormalizeApiTrait;

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof News;
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
        $mappedNews = [];
        $channelRSS = $data['channel'] ?? [];
        $itemsRSS = $channelRSS['item'] ?? [];
        foreach ($itemsRSS as $item) {
            $news = $this->denormalizeShow($item, $type, $format, $context);
            $mappedNews[] = $news;
        }
        return $mappedNews;
    }

    public function denormalizeShow($data, string $type, string $format = null, array $context = [])
    {
        /** @var News $entity */
        $entity = new $type();
        $entity->setTitle($data['title'] ?? null);
        $entity->setUrl($data['link'] ?? null);
        $entity->setDescription($data['description'] ?? null);
        $entity->setImageUrl($data['enclosure']['@attributes']['url'] ?? null);
        $entity->setDate(
            array_key_exists('pubDate', $data) ?
                $this->getCorrectDate($data['pubDate']) :
                null);

        return $entity;
    }

    public function getCorrectDate($date) {
        if ($date) {
            $pubDate = DateTimeService::getDateTimeFromString($date);
            return $pubDate->setTimezone(new \DateTimeZone('UTC'));
        }
        return null;
    }
}