<?php


namespace App\Serializer\Denormalize\Mapped;

use App\Helper\DenormalizeApiTrait;
use App\Helper\InterestsDenormalizeApiTrait;
use App\Helper\Mapped\Student;
use App\Helper\Role\AbstractUserRole;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class StudentDenormalizer implements ContextAwareDenormalizerInterface
{
    use InterestsDenormalizeApiTrait;

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Student and $context['api'] ?? null == 'mapped';
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $entityMapped = new Student();
        /** @var \App\Entity\Student $student */
        $student = $context['student'];
        if ($student) {
            $entityMapped->setId($student->getId());
            $entityMapped->setEmail($student->getEmail());
            $entityMapped->setGradeBookId($student->getGradeBookId());
            $entityMapped->setAvatarImageUrl($student->getAvatarImageUrl());
            $entityMapped->setPhone($student->getPhone());
        }
        $entityMapped->setExternalGuid($data['id'] ?? '');
        $entityMapped->setName($data['name'] ?? null);
        $entityMapped->setBitrixId($data['bitrixId'] ?? null);
        if (array_key_exists('birthday', $data))
            $entityMapped->setBirthday(DenormalizeApiTrait::getDateTimeFromString($data['birthday']));
        $entityMapped->setSex($data['sex'] ?? null);
        $entityMapped->setCity($data['city'] ?? null);
        $entityMapped->setGroup($data['group'] ?? null);
        $entityMapped->setCourse($data['course'] ?? null);
        $entityMapped->setSpeciality($data['speciality'] ?? null);
        $entityMapped->setTrainingPeriod($data['trainingPeriod'] ?? null);
        $entityMapped->setTutor($data['tutor'] ?? null);
        $entityMapped->setInterests(
            array_key_exists('interests', $data) ?
                $this->getStudentInterestsMapped($data['interests'], $context['student']) :
                null
        );
        $entityMapped->setTrainingStartYear($data['trainingStartYear'] ?? null);
        $entityMapped->setTrainingEndYear($data['trainingEndYear'] ?? null);
        return $entityMapped;
    }
}