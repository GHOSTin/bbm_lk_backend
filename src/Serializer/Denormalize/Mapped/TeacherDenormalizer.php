<?php


namespace App\Serializer\Denormalize\Mapped;

use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\Teacher;
use App\Helper\Role\AbstractUserRole;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class TeacherDenormalizer implements ContextAwareDenormalizerInterface
{
    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Teacher and $context['api'] ?? null == 'mapped';
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $entityMapped = new Teacher();
        /** @var Teacher $teacher */
        $teacher = $context['teacher'];
        if ($teacher) {
            $entityMapped->setId($teacher->getId());
            $entityMapped->setEmail($teacher->getEmail());
            $entityMapped->setAvatarImageUrl($teacher->getAvatarImageUrl());
            $entityMapped->setPhone($teacher->getPhone());
        }
        $entityMapped->setExternalGuid($data['id'] ?? '');
        $entityMapped->setFirstName($data['firstName'] ?? null);
        $entityMapped->setLastName($data['lastName'] ?? null);
        $entityMapped->setName($data['name'] ?? null);
        if (array_key_exists('birthday', $data))
            $entityMapped->setBirthday(DenormalizeApiTrait::getDateTimeFromString($data['birthday']));
        $entityMapped->setSex($data['sex'] ?? null);
        $entityMapped->setType($data['type'] ?? null);
        // TODO Пока нет функционала со списком достижений преподавателя
        $entityMapped->setInterests([]);
        // TODO Пока во внешем api нет стажа работы
        $entityMapped->setExperience($data['experience'] ?? null);
        $entityMapped->setNameSubject($data['nameSubject'] ?? null);
        $entityMapped->setPosition($data['position'] ?? null);
        $entityMapped->setSpeciality($data['speciality'] ?? null);
        return $entityMapped;
    }
}