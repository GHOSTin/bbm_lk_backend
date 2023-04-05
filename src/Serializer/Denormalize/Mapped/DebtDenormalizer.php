<?php


namespace App\Serializer\Denormalize\Mapped;


use App\Entity\AbstractUser;
use App\Entity\Teacher;
use App\Helper\Mapped\Debt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class DebtDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return new $type() instanceof Debt;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $entities = [];
        foreach ($data as $item) {
            $entity = new  Debt();
            $entity->setDoc($item['doc'] ?? null);
            $entity->setDate($this->getCorrectTimeDebt($item['date'] ?? null));
            $entity->setPeriod($item['period'] ?? null);
            $entity->setControl($item['control'] ?? null);
            $entity->setSubject($item['subject'] ?? null);
            $entity->setSubjectId($item['subjectId'] ?? null);
            $entity->setTeacher($item['teacher'] ?? null);
            $entity->setTeacherId($item['teacher_id'] ?? null);

            if(array_key_exists('teacher_id', $item) && $item['teacher_id'])
            {
                $teacher = $this->entityManager->getRepository(AbstractUser::class)
                    ->findOneBy(['externalGuid' => $item['teacher_id']]);
                $entity->setTeacherAvatar($teacher ? $teacher->getAvatarImageUrl() : null);
            }

            $entities[] = $entity;
        }
        return $entities;
    }

    public function getCorrectTimeDebt($time) {
        if ($time) {
            $dateTime = new \DateTime($time, new \DateTimeZone('Asia/Yekaterinburg'));
            return $dateTime->setTimezone(new \DateTimeZone('UTC'));
        }
        return null;
    }
}