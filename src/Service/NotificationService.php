<?php


namespace App\Service;

use App\Entity\AbstractUser;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NotificationService extends AbstractService
{
    protected $mailServer;

    public function __construct(EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                SerializerInterface $serializer,
                                MailService $mailService)
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->mailServer = $mailService;

    }

    public function sendNotificationTeacherSignUpAction(Teacher $teacher, $password)
    {
        $data = [
            'firstName' => $teacher->getFirstName(),
            'lastName' => $teacher->getLastName(),
            'email' => $teacher->getEmail(),
            'password' => $password,
        ];
        $subject = 'Регистрация';
        $view = 'notification_email\teacher\sign_up.html.twig';
        return $this->mailServer->send($teacher->getEmail(), $subject, $view, $data);
    }

    public function sendNotificationSignUp(AbstractUser $user, $password) {
        $data = [
            'email' => $user->getEmail(),
            'password' => $password,
        ];
        $subject = 'Регистрация';
        $view = 'notification_email/sign_up.html.twig';
        return $this->mailServer->send($user->getEmail(), $subject, $view, $data);
    }

    public function sendNotificationResetPassword(AbstractUser $user, $resetToken, $tokenLifetime) {
        $subject = 'Сброс пароля';
        $data = [
            'resetToken' => $resetToken,
            'tokenLifetime' => $tokenLifetime,
        ];
        $view = 'notification_email/reset_password.html.twig';
        return $this->mailServer->send($user->getEmail(), $subject, $view, $data);
    }

    public function sendNotificationChangePassword(AbstractUser $user, $password) {
        $data = [
            'email' => $user->getEmail(),
            'password' => $password,
        ];
        $subject = 'Пароль изменен';
        $view = 'notification_email/change_password.html.twig';
        return $this->mailServer->send($user->getEmail(), $subject, $view, $data);
    }
}