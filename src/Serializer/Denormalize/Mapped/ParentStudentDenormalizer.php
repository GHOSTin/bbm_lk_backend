<?php


namespace App\Serializer\Denormalize\Mapped;

use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\ParentStudent;
use App\Helper\Mapped\Student;
use App\Helper\Role\AbstractUserRole;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class ParentStudentDenormalizer implements ContextAwareDenormalizerInterface
{
    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof ParentStudent and $context['api'] ?? null == 'mapped';
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $entityMapped = new ParentStudent();

        /** @var \App\Entity\ParentStudent $parentStudent */
        $parentStudent = $context['parent'];

        /** @var Student|null $studentProfile */
        $studentProfile = $context['studentProfile'];

        if ($parentStudent) {
            $entityMapped->setId($parentStudent->getId());
            $entityMapped->setEmail($parentStudent->getEmail());
            $entityMapped->setAvatarImageUrl($parentStudent->getAvatarImageUrl());
            $entityMapped->setPhone($parentStudent->getPhone());
            $entityMapped->setStudentId($data['student_id']);
        }
        if ($studentProfile)
            $entityMapped->setStudentFullName($studentProfile->getName());
        $entityMapped->setGradeBookId($data['gradebook_id'] ?? null);
        $entityMapped->setStudentId($data['student_id'] ?? null);
        $entityMapped->setName($data['name'] ?? null);
        $entityMapped->setGroup($data['group'] ?? null);
        $entityMapped->setLevel($data['level'] ?? null);
        $entityMapped->setStudentId($data['student_id']);
        // TODO Пока по методу родителя нет externalGuid
        $entityMapped->setExternalGuid('');
        return $entityMapped;
    }
}