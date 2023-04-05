<?php


namespace App\Serializer\Normalize;


use App\Helper\DenormalizeApiTrait;
use App\Helper\Mapped\AbstractUser;
use App\Helper\Mapped\ParentStudent;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\Teacher;
use App\Helper\Role\AbstractUserRole;
use App\Service\DateTimeService;
use App\Service\ProfileService;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ProfileNormalizer implements ContextAwareNormalizerInterface
{
    use DenormalizeApiTrait;

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param mixed $object Object to normalize
     * @param string $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool|\ArrayObject|null \ArrayObject is used to make sure an empty object is encoded as an object not an array
     *
     * @throws InvalidArgumentException   Occurs when the object given is not a supported type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     * @throws ExceptionInterface         Occurs for all the other cases of errors
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        /** @var Student|ParentStudent|Teacher $object */
        $user = [
            'avatarImageUrl' => $object->getAvatarImageUrl(),
            'fullName' => $object->getName(),
            'email' => $object->getEmail(),
            'externalGuid' => $object->getExternalGuid(),
            'phone' => $object->getPhone(),
            'birthday' => DateTimeService::getDateTimeToIso8601($object->getBirthday()),
            'age' => ProfileService::getAge($object->getBirthday()),
            'city' => $object->getCity(),
            'role' => $object->getRole(),

            'fullNameParent' => null,
            'parentId' => null,
            'fullNameStudent' => null,
            'group' => null,
            'speciality' => null,
            'trainingPeriod' => null,
            'trainingStartYear' => null,
            'trainingEndYear' => null,
            'course' => null,

            'studentId' => null,
            'level' => null,

            // TODO Пока во внешем api нет стажа работы
            'experience' => null,
            'position' => null,
            'nameSubject' => null,
            'firstName' => null,
            'lastName' => null,
            'interests' => [],
            'myInterests' => array_key_exists('myInterests', $context) ? $context['myInterests'] : null
        ];
        switch (true) {
            case $object instanceof Student:
                $student = [
                    /** TODO Пока нет отдельного метода для родителя */
                    'fullNameParent' => null,
                    'parentId' => null,

                    'fullNameStudent' => $object->getName(),
                    'group' => $object->getGroup(),
                    'speciality' => $object->getSpeciality(),

                    'trainingPeriod' => $object->getTrainingPeriod(),
                    'trainingStartYear' => $object->getTrainingStartYear(),
                    'trainingEndYear' => $object->getTrainingEndYear(),
                    'course' => $object->getCourse(),
                ];
                $student['interests'] = $this->getSerializer()->normalize($object->getInterests(), 'json', ['groups' => 'show']);
                $user = array_merge($user, $student);
                break;
            case $object instanceof ParentStudent:
                $parent = [
                    'studentId' => $object->getStudentId(),
                    'fullNameStudent' => $object->getStudentFullName(),
                    'level' => $object->getLevel()
                ];
                $user = array_merge($user, $parent);
                break;
            case $object instanceof Teacher:
                $teacher = [
                    /** TODO В методе препода нет должности и название предмета */
                    'position' => $object->getType(),
                    'nameSubject' => $object->getNameSubject(),
                    // TODO Пока во внешем api нет стажа работы
                    'experience' => $object->getExperience(),
                    'speciality' => $object->getSpeciality(),

                    'firstName' => $object->getFirstName(),
                    'lastName' => $object->getLastName(),
                ];
                $teacher['interests'] = $this->getSerializer()->normalize($object->getInterests(), 'json', ['groups' => 'show']);
                $user = array_merge($user, $teacher);
                break;
        }
        return $user;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof AbstractUser and $context['api'] ?? null == 'internal';
    }
}