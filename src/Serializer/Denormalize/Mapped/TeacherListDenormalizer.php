<?php

namespace App\Serializer\Denormalize\Mapped;

use App\Entity\Teacher;
use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\Subject;
use App\Helper\Mapped\TeacherList;
use App\Service\ApiExternal\ProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class TeacherListDenormalizer implements ContextAwareDenormalizerInterface
{
    use DenormalizeApiTrait;

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $mappedTeachers = [];
        $teachers = $this->em->getRepository(Teacher::class)->getTeachersIndexByExternalGuid();
        foreach ($data as $datum) {
            $teacherId = $datum['teacherID'] ?? $datum['id'] ?? null;
            $teacher = $teachers[$teacherId] ?? null;

            /** @var TeacherList $entity */
            $entity = new $type();
            $entity->setFullName($datum['teacher'] ?? $datum['username'] ?? null);
            $entity->setExternalGuid($datum['teacherID'] ?? $datum['id'] ?? null);
            $entity->setAvatarImageUrl($teacher ? $teacher->getAvatarImageUrl() : null);

            $mappedTeachers[] = $entity;
        }
        usort($mappedTeachers, function (TeacherList $a, TeacherList $b) {
            return mb_strtolower($a->getFullName()) > mb_strtolower($b->getFullName());
        });
        return $mappedTeachers;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof TeacherList;
    }

}