<?php


namespace App\Serializer\Normalize\ApiExternal;


use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Helper\Role\AbstractUserRole;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class AuthNormalizer implements ContextAwareNormalizerInterface
{
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
        $entity = [];
        switch ($object->getRole()) {
            case AbstractUserRole::ROLE_STUDENT:
                $entity = [
                    'email' => $object->getEmail(),
                    'gradebookId' => (string)$object->getGradeBookId(),
                    'username' => $object->getSurname(),
                ];
                break;
            case AbstractUserRole::ROLE_PARENT:
                $entity = [
                    'email' => $object->getEmail(),
                    'gradebookId' => (string)$object->getGradeBookId(),
                ];
                break;
            case AbstractUserRole::ROLE_TEACHER:
                $entity = [
                    'email' => $object->getEmail(),
                    'firstName' => $object->getFirstName(),
                    'lastName' => $object->getLastName(),
                ];
                break;
        }
        return $entity;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof AbstractUser and $context['api'] ?? null == 'external';
    }
}