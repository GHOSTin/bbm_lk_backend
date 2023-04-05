<?php


namespace App\Serializer\Denormalize;


use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Helper\Role\AbstractUserRole;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class AuthDenormalizer implements ContextAwareDenormalizerInterface
{

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof AbstractUser and $context['api'] ?? null == 'internal';
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $role = $context['role'] ?? null;
        switch ($role) {
            case 'student':
                $entity = new Student();
                break;
            case 'parent':
                $entity = new ParentStudent();
                break;
            case 'teacher':
                $entity = new Teacher();
                break;
            default:
                return null;
        }
        $entity->setEmail($data['email'] ?? null);
        if (in_array($entity->getRole(), [AbstractUserRole::ROLE_STUDENT, AbstractUserRole::ROLE_PARENT])) {
            $entity->setGradeBookId($data['gradeBookId'] ?? null);
            $entity->setSurname($data['surname'] ?? null);
        }

        return $entity;
    }
}