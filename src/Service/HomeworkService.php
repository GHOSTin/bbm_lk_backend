<?php


namespace App\Service;


use App\Controller\Api\UploadFileController;
use App\Entity\Student;
use App\Helper\Mapped\Homework;
use App\Helper\Mapped\Subject;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeworkService
{
    public $fileDir;

    private $mailService;

    private $container;

    /**
     * HomeworkService constructor.
     * @param ContainerInterface $container
     * @param MailService $mailService
     */
    public function __construct(ContainerInterface $container, MailService $mailService)
    {
        $this->container = $container;
        $this->fileDir = $container->get('kernel')->getProjectDir() . '/public';
        $this->mailService = $mailService;
    }

    public function sendAction(Homework $homework)
    {
        $emailForHomework = $this->container->getParameter('email_for_homework');
        $data = [
            'studentName' => $homework->getStudent(),
            'group' => $homework->getGroup(),
            'date' => $homework->getDate(),
            'description' => $homework->getDescription(),
            'subjectName' => $homework->getSubjectName()
        ];
        $data['attachments'] = $homework->getAttachments();
        $data['path'] = $this->fileDir;
        $subject = 'Домашнее задание';
        $view = 'homework/homework.html.twig';

        $this->mailService->send($emailForHomework, $subject, $view, $data);
    }

    public function getHomeworkContent(Student $user)
    {
        $profileService = $this->container->get('App\Service\ApiExternal\ProfileService');
        $subjectsService = $this->container->get('App\Service\SubjectService');
        $profile = $profileService->getMyProfile($user);
        $subjects = $subjectsService->getSubjectsStudent($user->getExternalGuid(), $user->getTokenExternal()->getToken());
        $maxFileSize = $this->getMaxUploadSize();
        return [
            'fullName' => $profile->getName(),
            'group' => $profile->getGroup(),
            'subjects' => $subjects,
            'maxUploadFileSize' => $maxFileSize
        ];
    }

    public function getMaxUploadSize() {
        $postMaxSize = $this->convert2Bytes(ini_get('post_max_size'));
        $uploadMaxFileSize = $this->convert2Bytes(ini_get('upload_max_filesize'));
        $memory_limit = $this->convert2Bytes(ini_get('memory_limit'));
        return min($postMaxSize, $uploadMaxFileSize, $memory_limit);
    }

    private function convert2Bytes($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }

}