<?php


namespace App\Service;


use App\Entity\AbstractUser;
use App\Entity\Device;
use App\Entity\ResetPasswordRequest;
use App\Entity\Student;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Status\DeviceStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\TooManyPasswordRequestsException;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordRequestService extends AbstractService
{
    use ResetPasswordRequestTrait;

    protected $notificationService;
    protected $resetPasswordHelper;
    protected $userService;
    protected $userPasswordEncoder;


    public function __construct(EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                SerializerInterface $serializer,
                                NotificationService $notificationService,
                                ResetPasswordHelperInterface $resetPasswordHelper,
                                UserService $userService,
                                UserPasswordEncoderInterface $encoder
    )
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->notificationService = $notificationService;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->userService = $userService;
        $this->userPasswordEncoder = $encoder;
    }


    public function generateResetPasswordToken(AbstractUser $user) {
        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        }
        catch (TooManyPasswordRequestsException $exception) {
            ApiExceptionHandler::errorApiHandlerMessage('Too Many Request Reset Password',
                'Too Many Request Reset Password',
                ResponseCode::HTTP_TOO_MANY_REQUESTS);
        }
        $this->notificationService->sendNotificationResetPassword($user, $resetToken, $this->resetPasswordHelper->getTokenLifetime());
    }

    public function generateNewPassword(AbstractUser $user, $token) {
        $this->resetPasswordHelper->removeResetRequest($token);
        $password = $this->userService->generatePassword();
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $this->em->persist($user);
        $this->em->flush();

        $this->notificationService->sendNotificationChangePassword($user, $password);
    }
}