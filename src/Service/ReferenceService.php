<?php


namespace App\Service;


use App\Entity\AbstractUser;
use App\Entity\Device;
use App\Entity\ParentStudent;
use App\Entity\Reference;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\UserReference;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Mapped\Homework;
use App\Helper\Role\AbstractUserRole;
use App\Helper\Status\DeviceStatus;
use App\Helper\Status\ReferenceStatus;
use App\Service\ApiExternal\ProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferenceService extends AbstractService
{
    /**
     * @var ProfileService $externalProfileService
     */
    protected $externalProfileService;

    /**
     * @var ContainerInterface
     */
    protected $container;

    const RECEIVE_REFERENCE_INTERVAL = 5;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ProfileService $profileService,
        ContainerInterface $container
    )
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->externalProfileService = $profileService;
        $this->container = $container;
    }

    public function getListReference($user) {
        $references = $this->em->getRepository(Reference::class)->findBy([
            'status' => ReferenceStatus::ACTIVE
        ]);
        $profile = $this->externalProfileService->getMyProfile($user);
        $dateOnReferenceArray = $this->getWorkingDaysArray();
        return [
            'fullName' => $profile->getName(),
            'group' => $profile->getGroup(),
            'references' => $references,
            'dateOnReferenceArray' => $dateOnReferenceArray,
        ];
    }

    public function createUserReference($user, $data) {
        /** @var Reference $reference */
        $reference = $this->em->getRepository(Reference::class)->find($data['referenceId']);
        if (!$reference) {
            ApiExceptionHandler::errorApiHandlerObjectNotFoundMessage(
                $data['referenceId'],
                ResponseCode::HTTP_BAD_REQUEST,
                'Reference Not Found'
            );
        }
        $userReference = new UserReference();
        $userReference->setUser($user);
        $userReference->setReference($reference);
        $userReference->setNote($data['note'] ?: null);
        try {
            $userReference->setDateOnReference(DateTimeService::getDateTimeFromString($data['dateOnReference']));
        } catch (\Exception $e) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Invalid dateOnReference'
            );
        }
        $userReference->setReceiveDate($this->getReceiveDate($userReference->getDateOnReference()));
        $this->em->persist($userReference);
        $this->em->flush();
        return $userReference;
    }

    public function sendAction(UserReference $userReference)
    {
        $mailService = $this->container->get('App\Service\MailService');
        $emailForReference = $this->container->getParameter('email_for_user_reference');
        $user = $userReference->getUser();
        $guid = $user instanceof ParentStudent ? $user->getStudentExternalId() : $user->getExternalGuid();
        /** @var \App\Helper\Mapped\Student $studentProfile */
        $studentProfile = $this->externalProfileService->getStudentProfile($guid, $user->getTokenExternal()->getToken());
        $data = [
            'studentName' => $studentProfile->getName(),
            'group' => $studentProfile->getGroup(),
            'course' => $studentProfile->getCourse(),
            'dateOnReference' => $userReference->getDateOnReference(),
            'referenceName' => $userReference->getReference()->getName(),
            'note' => $userReference->getNote(),
        ];
        $subject = 'Заказ справки';
        $view = 'reference/user_reference.html.twig';
        $mailService->send($emailForReference, $subject, $view, $data);
    }

    private function getReceiveDate() {
        $receiveDate = new \DateTime('now', new \DateTimeZone('UTC'));
        $receiveDate->modify('+' . self::RECEIVE_REFERENCE_INTERVAL . ' weekdays midnight');
        return $receiveDate;
    }

    private function getWorkingDaysArray() {
        $period = new \DatePeriod(
            new \DateTime('today midnight', new \DateTimeZone('UTC')),
            \DateInterval::createFromDateString('+1 weekday'),
            $this->getReceiveDate()->modify('+1 weekdays')
        );

        $workingDateArray = [];
        /** @var \DateTime $date */
        foreach ($period as $date) {
            $workingDateArray[] = $date;
        }
        return $workingDateArray;
    }
}