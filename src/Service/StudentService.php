<?php


namespace App\Service;


use App\Helper\Mapped\GroupStudents;
use App\Helper\Mapped\Student;
use App\Helper\Mapped\StudentList;
use App\Helper\Mapped\TeacherList;
use App\Helper\Role\AbstractUserRole;

class StudentService extends \App\Service\ApiExternal\AbstractService
{
    const CLASSMATES_ROUTE = '/groupmates/';
    const STUDENTS_BY_TEACHER_ROUTE = '/feed/students/';

    public function getStudentsByStudent($studentId, $token)
    {
        // TODO Не работает метод одногруппников
    }

    public function getStudentsByTeacher($teacherId, $token)
    {
        $this->setToken($token);
        $url = self::STUDENTS_BY_TEACHER_ROUTE;
        $dataStudents = $this->getContent($this->makeGetRequest($url, null, null));
        $groupStudents = $this->serializer->deserialize(
            $dataStudents,
            GroupStudents::class,
            "json"
        );
        return $groupStudents;
    }

    public function getClassmates($studentId, $token)
    {
        $this->setToken($token);
        $url = self::CLASSMATES_ROUTE . $studentId;
        // TODO Пока не работает внешний метод одногруппников
        $dataStudents = $this->getContent($this->makeGetRequest($url, null, null));
        $classmates = $this->serializer->deserialize(
            $dataStudents,
            StudentList::class,
            "json",
            [
                'group' => 'denormalizeIndex'
            ]
        );
        return $classmates;
    }

}