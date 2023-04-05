<?php

namespace App\Controller\Api;

use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Teacher;
use App\Helper\Mapped\Subject;
use App\Helper\Mapped\TeacherList;
use App\Service\SubjectService;
use App\Service\TeacherService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TeacherApiController
 * @package  App\Controller\Api
 */
class TeacherApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Teacher")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Teacher List",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="teachers", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=TeacherList::class, groups={"show"})
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @SWG\Parameter(
     *   name="apikey",
     *   type="string",
     *   required=true,
     *   in="header",
     *   description="auth user's apikey"
     * )
     *
     * @Route("/teachers", name="api_teachers", methods={"GET"})
     * @param Request $request
     * @param TeacherService $teacherService
     * @return Response
     */
    public function getTeachers(Request $request, TeacherService $teacherService)
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        if ($user instanceof ParentStudent) {
            $guid = $user->getStudentExternalId();
        } else {
            $guid = $user->getExternalGuid();
        }

        if ($user instanceof Teacher) {
            $teachers = $teacherService->getTeachersByTeacher($guid, $user->getTokenExternal()->getToken());
        }
        else {
            $teachers = $teacherService->getTeachersByStudent($guid, $user->getTokenExternal()->getToken());
        }
        if (!empty($teachers)) {
            $data['data']['teachers'] = $teachers;
        } else {
            $data['data']['teachers'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['show']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
