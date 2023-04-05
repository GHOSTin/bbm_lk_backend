<?php


namespace App\Service;


use App\Entity\AbstractUser;
use App\Entity\Device;
use App\Entity\ParentStudent;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Role\AbstractUserRole;
use App\Helper\Status\DeviceStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityService extends AbstractService
{
    protected $userPasswordEncoder;
    protected $externalSecurityService;
    protected $notificationService;
    protected $resetPasswordRequestService;
    protected $userService;

    public function __construct(EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                SerializerInterface $serializer,
                                UserPasswordEncoderInterface $encoder,
                                \App\Service\ApiExternal\SecurityService $externalSecurityService,
                                NotificationService $notificationService,
                                ResetPasswordRequestService $passwordRequestService,
                                UserService $userService
    )
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->userPasswordEncoder = $encoder;
        $this->externalSecurityService = $externalSecurityService;
        $this->notificationService = $notificationService;
        $this->resetPasswordRequestService = $passwordRequestService;
        $this->userService = $userService;
    }


    public function getUserForLogin($email, $password) {
        /** @var AbstractUser $user */
        $user = $this->em->getRepository(AbstractUser::class)->findOneBy(['email' => $email]);
        if (!$user or !$this->userPasswordEncoder->isPasswordValid($user, $password)) {
            ApiExceptionHandler::errorApiHandlerMessage(null,
                'Email or Password Incorrect',
                ResponseCode::HTTP_UNAUTHORIZED);
        }

        return $this->createDevice($user);
    }

    public function signUp($data) {
        $checkExistStudent = $this->em->getRepository(Student::class)->findOneBy(
            [
                'email' => $data['email'],
                'gradeBookId' => $data['gradeBookId'],
            ]
        );

        $checkExistParent = $this->em->getRepository(ParentStudent::class)->findOneBy(
            [
                'email' => $data['email'],
                'gradeBookId' => $data['gradeBookId'],
            ]
        );
        if ($checkExistStudent or $checkExistParent) {
            ApiExceptionHandler::errorApiHandlerMessage(null,
                'User already exist',
                ResponseCode::HTTP_OBJECT_ALREADY_EXIST);
        }
        /** @var AbstractUser $user */
        $user = $this->externalSecurityService->externalSignUp($data);
        $this->validate($user);
        $password = $this->userService->generatePassword();
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $this->em->persist($user);
        $this->em->flush();
        $this->notificationService->sendNotificationSignUp($user, $password);
        return $user;
    }

    public function logoutToken($apikey) {
        $device = $this->em->getRepository(Device::class)->findOneBy(['token' => $apikey]);
        if (!$device)
            ApiExceptionHandler::errorApiHandlerMessage(null,
                'Token not Found',
                ResponseCode::HTTP_NOT_FOUND);
        $device->setStatus(DeviceStatus::INACTIVE);
        $this->em->persist($device);
        $this->em->flush();
    }

    public function resetPasswordGenerateToken($email) {
        $user = $this->em->getRepository(AbstractUser::class)->findOneBy([
            'email' => $email
        ]);
        if (!$user) {
            ApiExceptionHandler::errorApiHandlerMessage(null,
                'User not Found',
                ResponseCode::HTTP_NOT_FOUND);
        }

        $this->resetPasswordRequestService->generateResetPasswordToken($user);
    }

    private function createDevice(AbstractUser $user) {
        $device = new Device();
        $device->setUser($user);
        $this->em->persist($device);
        $this->em->flush();
        return $device;
    }
}