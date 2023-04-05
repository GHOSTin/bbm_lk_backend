<?php

namespace App\Serializer\Denormalize\Mapped;

use App\Entity\Teacher;
use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\GroupStudents;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\StudentList;
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

class GroupStudentsDenormalizer implements ContextAwareDenormalizerInterface
{
    use DenormalizeApiTrait;

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $mappedStudents = [];
        $students = $this->em->getRepository(\App\Entity\Student::class)->getStudentsIndexByExternalGuid();
        foreach ($data as $datum) {
            $mappedStudent = $this->getSerializer()->denormalize(
                $datum,
                StudentList::class,
                $format,
                [
                    'students' => $students,
                    'group' => 'denormalizeShow',
                ]
            );
            $mappedStudents[] = $mappedStudent;
        }
        usort($mappedStudents, function (StudentList $a, StudentList $b) {
            return mb_strtolower($a->getFullName()) > mb_strtolower($b->getFullName());
        });

        $studentsByGroup = [];
        /** @var StudentList $mappedStudent */
        foreach ($mappedStudents as $mappedStudent) {
            $studentsByGroup[$mappedStudent->getGroup()][] = $mappedStudent;
        }
        uksort($studentsByGroup, function ($a, $b) {
            return mb_strtolower($a) > mb_strtolower($b);
        });

        $groupStudents = [];
        foreach ($studentsByGroup as $keyGroup => $studentArray) {
            $entityMapped = new GroupStudents();
            $entityMapped->setGroup($keyGroup);
            $entityMapped->setGroupId($studentArray[0]->getGroupId());
            $entityMapped->setStudents($studentArray);
            $groupStudents[] = $entityMapped;
        }

        return $groupStudents;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof GroupStudents;
    }

}