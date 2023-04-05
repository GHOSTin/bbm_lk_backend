<?php


namespace App\Service\ApiExternal;



use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Helper\Mapped as Mapped;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\TokenExternal;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;

class ProfileService extends AbstractService
{
    private const method_profile_student_teacher = '/users/';
    private const method_profile_parent = '/parents/';
    private const method_profile_alt_teacher = '/teachers/';

    public function getMyProfile($user)
    {
        switch (true) {
            case $user instanceof Student:
                /** @var \App\Helper\Mapped\Student $profile */
                $profile = $this->getStudentProfile(
                    $user->getExternalGuid() ?? $user->getGradeBookId(),
                    $user->getTokenExternal()->getToken());
                if (!$user->getExternalGuid()) {
                    $user->setExternalGuid($profile->getExternalGuid());
                    $this->em->flush();
                }
                break;
            case $user instanceof ParentStudent:
                /** @var Mapped\ParentStudent $profile */
                $profile = $this->getParentProfile($user, $user->getTokenExternal()->getToken());
                if ($user->getStudentExternalId() != $profile->getStudentId()) {
                    $user->setStudentExternalId($profile->getStudentId());
                    $this->em->flush();
                }
                break;
            case $user instanceof Teacher:
                if ($user->getExternalGuid())
                    $profile = $this->getTeacherProfileByGuid(
                        $user->getExternalGuid(),
                        $user->getTokenExternal()->getToken()
                    );
                else
                    $profile = $this->getTeacherProfileByFistNameAndLastName(
                        $user->getFirstName(),
                        $user->getLastName(),
                        $user->getTokenExternal()->getToken()
                    );
                if (!$user->getExternalGuid()) {
                    $user->setExternalGuid($profile->getExternalGuid());
                    $this->em->flush();
                }
                break;
        }
        return $profile;
    }

    public function getStudentProfile($guidOrGradeBookId, $token) {
        $url = self::method_profile_student_teacher . $guidOrGradeBookId;
        $student = $this->em->getRepository(Student::class)->getStudentByGradeBookIdOrGuid($guidOrGradeBookId);
        $response = $this->makeGetRequest($url, null, null, $token);
        $dataJson = $this->getContent($response);
        $studentProfile = $this->serializer->deserialize(
            $dataJson,
            \App\Helper\Mapped\Student::class,
            'json',
            [
                'api' => 'mapped',
                'student' => $student
            ]
        );
        return $studentProfile;
    }

    public function getParentProfile(ParentStudent $parentStudent, $token) {
        $url = self::method_profile_parent;
        $response = $this->makeGetRequest($url, null, null, $token);
        $dataJson = $this->getContent($response);
        $data = $this->decodeContent($dataJson);
        $studentProfile = null;
        if (array_key_exists('student_id', $data)) {
            $studentProfile = $this->getStudentProfile($data['student_id'], $token);
        }
        $parentStudentProfile = $this->serializer->deserialize(
            $dataJson,
            \App\Helper\Mapped\ParentStudent::class,
            'json',
            [
                'api' => 'mapped',
                'parent' => $parentStudent,
                'studentProfile' => $studentProfile,
            ]
        );
        return $parentStudentProfile;
    }

    public function getTeacherProfileByFistNameAndLastName($firstName, $lastName, $token) {
        $url = self::method_profile_student_teacher . '?firstName=' . $firstName . '&lastName=' . $lastName;
        $teacher = $this->em->getRepository(Teacher::class)->findOneBy([
            'firstName' => $firstName,
            'lastName' => $lastName
        ]);
        $response = $this->makeGetRequest($url, null, null, $token);
        $dataJson = $this->getContent($response);
        $teacherProfile = $this->serializer->deserialize(
            $dataJson,
            \App\Helper\Mapped\Teacher::class,
            'json',
            [
                'api' => 'mapped',
                'teacher' => $teacher,
            ]
        );
        return $teacherProfile;
    }

    public function getTeacherProfileByGuid($guid, $token) {
        $url = self::method_profile_alt_teacher . $guid;
        $teacher = $this->em->getRepository(Teacher::class)->findOneBy([
            'externalGuid' => $guid,
        ]);
        $response = $this->makeGetRequest($url, null, null, $token);
        $dataJson = $this->getContent($response);
        $teacherProfile = $this->serializer->deserialize(
            $dataJson,
            \App\Helper\Mapped\Teacher::class,
            'json',
            [
                'api' => 'mapped',
                'teacher' => $teacher,
            ]
        );
        return $teacherProfile;
    }
}