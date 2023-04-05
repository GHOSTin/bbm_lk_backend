<?php


namespace App\Service;


use App\Helper\Mapped\Subject;
use App\Helper\Role\AbstractUserRole;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubjectService extends \App\Service\ApiExternal\AbstractService
{
    const SUBJECTS_BY_STUDENT_ROUTE = '/subjects/students';
    const SUBJECTS_BY_GROUP_ROUTE = '/subjects/';
    const SUBJECTS_BY_TEACHER_ROUTE = '/subjects/teachers';
    const NOW_DATE_QUERY = '?now';

    protected $container;

    public function __construct(
        $apiExternalDomain,
        $apiVersion,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ContainerInterface $container
    )
    {
        parent::__construct($apiExternalDomain, $apiVersion, $entityManager, $validator, $serializer);
        $this->container = $container;
    }

    public function getSubjectsStudent($studentId, $token)
    {
        $this->setToken($token);
        $url = self::SUBJECTS_BY_STUDENT_ROUTE . "/" .$studentId . self::NOW_DATE_QUERY;
        $dataWithTeacher = $this->getContent($this->makeGetRequest($url, null, null));
        $subjects = $this->serializer->deserialize($dataWithTeacher, Subject::class, "json");
        return $subjects;
    }

    public function getSubjectsTeacher($teacherId, $token)
    {
        $this->setToken($token);
        $url = self::SUBJECTS_BY_TEACHER_ROUTE . "/" .$teacherId;
        $dataWithTeacher = $this->getContent($this->makeGetRequest($url, null, null));
        $profileService = $this->container->get('App\Service\ProfileService');
        $teacherProfile = $profileService->getProfile($teacherId, AbstractUserRole::ROLE_TEACHER, $token);
        $subjects = $this->serializer->deserialize(
            $dataWithTeacher,
            Subject::class,
            "json",
            [
                'teacherProfile' => $teacherProfile
            ]
        );
        return $subjects;
    }

}