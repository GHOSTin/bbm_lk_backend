<?php

namespace App\Serializer\Denormalize\Mapped;

use App\Entity\Teacher;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\Subject;
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

class SubjectDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var EntityManagerInterface  */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $subjects = [];
        /** @var \App\Helper\Mapped\Teacher|null $teacherProfile */
        $teacherProfile = $context['teacherProfile'] ?? null;
        $teacher = null;
        if ($teacherProfile) {
            $teacher = $this->em->getRepository(Teacher::class)->findOneBy(
                [
                    'externalGuid' => $teacherProfile->getExternalGuid()
                ]
            );
        }
        foreach ($data as $datum) {
            /** @var Subject $entity */
            if (!$teacherProfile) {
                $teacher = $this->em->getRepository(Teacher::class)->findOneBy(
                    [
                        'externalGuid' => $datum['teacherId']
                    ]
                );
            }

            $entity = new $type();
            $entity->setName($datum['subject']);
            $entity->setSemester($datum['semester']);
            $entity->setTeacherName($datum['teacher'] ?? $teacherProfile->getName() ?? null);
            $entity->setTeacherExternalId($datum['teacherId'] ?? $teacherProfile->getExternalGuid() ?? null);
            $entity->setTeacherAvatar($teacher ? $teacher->getAvatarImageUrl() : null);
            $entity->setGroup($datum['group'] ?? null);
            $entity->setGroupId($datum['groupId'] ?? null);
            $entity->setControl($datum['control'] ?? null); //TODO: add control and literature

            array_push($subjects, $entity);
        }
        return $subjects;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Subject;
    }

}