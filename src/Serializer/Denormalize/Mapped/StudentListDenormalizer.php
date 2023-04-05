<?php

namespace App\Serializer\Denormalize\Mapped;

use App\Entity\Student;
use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\StudentList;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class StudentListDenormalizer implements ContextAwareDenormalizerInterface
{
    use DenormalizeApiTrait;

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
        $mappedStudentsList = [];
        $students = $context['students'] ?? null;
        if (!$students)
            $students = $this->em->getRepository(Student::class)->getStudentsIndexByExternalGuid();

        foreach ($data as $datum) {
            $mappedStudent = $this->denormalizeShow($datum, $type, $format, ['students' => $students]);
            $mappedStudentsList[] = $mappedStudent;
        }

        usort($mappedStudentsList, function (StudentList $a, StudentList $b) {
            return mb_strtolower($a->getFullName()) > mb_strtolower($b->getFullName());
        });
        
        return $mappedStudentsList;
    }

    public function denormalizeShow($data, string $type, string $format = null, array $context = [])
    {
        $students = $context['students'] ?? null;
        if (!$students)
            $students = $this->em->getRepository(Student::class)->getStudentsIndexByExternalGuid();

        $studentId = $data['id'] ?? null;
        $student = $students[$studentId] ?? null;

        /** @var StudentList $entity */
        $entity = new $type();
        $entity->setFullName($data['username'] ?? $data['name'] ?? null);
        $entity->setExternalGuid($data['id'] ?? null);
        $entity->setGroup($data['group'] ?? null);
        $entity->setGroupId($data['groupId'] ?? null);
        $entity->setAvatarImageUrl($student ? $student->getAvatarImageUrl() : null);

        return $entity;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof StudentList;
    }

}