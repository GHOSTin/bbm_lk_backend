<?php


namespace App\Service;


use App\Helper\Mapped\Student;
use App\Helper\Mapped\Subject;
use App\Helper\Mapped\TeacherList;
use App\Helper\Role\AbstractUserRole;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeacherService extends \App\Service\ApiExternal\AbstractService
{
    const TEACHERS_BY_STUDENT_ROUTE = '/teachers';
    const TEACHERS_BY_TEACHER_ROUTE = '/feed/teachers/';

    /**
     * @var ContainerInterface
     */
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

    public function getTeachersByStudent($studentId, $token)
    {
        $profileService = $this->container->get('App\Service\ProfileService');
        /** @var Student $studentProfile */
        $studentProfile = $profileService->getProfile($studentId, AbstractUserRole::ROLE_STUDENT, $token);
        $this->setToken($token);
        $url = self::TEACHERS_BY_STUDENT_ROUTE . '?group=' . $studentProfile->getGroup();
        $dataTeachers = $this->getContent($this->makeGetRequest($url, null, null));
        $teachers = $this->serializer->deserialize(
            $dataTeachers,
            TeacherList::class,
            "json"
        );
        return $teachers;
    }

    public function getTeachersByTeacher($teacherId, $token)
    {
        $this->setToken($token);
        $url = self::TEACHERS_BY_TEACHER_ROUTE;
        $dataTeachers = $this->getContent($this->makeGetRequest($url, null, null));
        $teachers = $this->serializer->deserialize(
            $dataTeachers,
            TeacherList::class,
            "json"
        );
        return $teachers;
    }

}