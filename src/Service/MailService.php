<?php


namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Twig\Environment;
use Twig\Error\Error;

class MailService
{
    /**
     * @var Environment
     */
    protected $templating;

    protected $container;

    protected $logger;

    public function __construct(
        Environment $templating,
        ContainerInterface $container,
        LoggerInterface $logger
    )
    {
        $this->templating = $templating;
        $this->container = $container;
        $this->logger = $logger;
    }

    private function getMailer() {
        $dsn = $this->container->getParameter('mailer_dsn');
        $transport = Transport::fromDsn($dsn);
        return new Mailer($transport);
    }

   
    public function send($email, $subject, $view, $data = [])
    {
        try {
            $mailer = $this->getMailer();
            $message = $this->create($email, $subject, $view, $data);
            $mailer->send($message);
            return true;
        }
        catch (\Error $error) {
            $this->logger->warning('Произошла ошибка при отправке сообщения на адрес ' . $email);
            return false;
        } catch (TransportExceptionInterface $e) {
            $this->logger->warning('Произошла ошибка при отправке сообщения на адрес ' . $email);
        }
    }

    public function create($email, $subject, $view, $data = [])
    {
        $message = new TemplatedEmail();
        $sender = $this->container->getParameter('mailer_sender');
        try {
            $message
                ->from($sender)
                ->subject($subject)
                ->to($email)
                // TODO Доп рассылка для разработчиков
//                ->cc('ctpz-developers@profsoft.pro')
                ->html(
                    $this->templating->render(
                        $view,
                        $data
                    ),
                    'text/utf-8'
                )
            ;
            if (key_exists('attachments', $data)) {
                foreach ($data['attachments'] as $homework) {
                    $message->attachFromPath($data['path'] . $homework);
                }
            }
        } catch (Error $e) {
            $e .= 1;
            $this->logger->warning('Произошла ошибка при отправке сообщения на адрес ' . $email);
        }
        return $message;
    }
}