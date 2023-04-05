<?php

namespace App\Controller\Api;

use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Teacher;
use App\Helper\Exception\ApiException;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Mapped\GroupStudents;
use App\Helper\Mapped\StudentList;
use App\Service\StudentService;
use App\Service\TeacherService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StudentApiController
 * @package  App\Controller\Api
 */
class StudentApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Students")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Student List By Groups For Teacher (USE ONLY TEACHER USER)",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="groups", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=GroupStudents::class, groups={"show"})
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden Access (For Student and Parents)",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
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
     * @Route("/teacher/students", name="api_students_for_teacher", methods={"GET"})
     * @param Request $request
     * @param StudentService $studentService
     * @return Response
     */
    public function getStudentsForTeacher(Request $request, StudentService $studentService)
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        if (!$user instanceof Teacher) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Доступ запрещен',
                ResponseCode::HTTP_FORBIDDEN
            );
        }
        $guid = $user->getExternalGuid();

        $groups = $studentService->getStudentsByTeacher($guid, $user->getTokenExternal()->getToken());
        if (!empty($groups)) {
            $data['data']['groups'] = $groups;
        } else {
            $data['data']['groups'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['show']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Students")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get classmates list (USING ONLY STUDENT AND PARENT USER)",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="classmates", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=StudentList::class, groups={"show"})
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden Access (For Teacher)",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Authentication Required",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
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
     * @Route("/classmates", name="api_students_classmates", methods={"GET"})
     * @param Request $request
     * @param StudentService $studentService
     * @return Response
     */
    public function getClassmates(Request $request, StudentService $studentService)
    {

        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        if ($user instanceof Teacher) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Доступ запрещен',
                ResponseCode::HTTP_FORBIDDEN
            );
        }

        if ($user instanceof ParentStudent) {
            $guid = $user->getStudentExternalId();
        }
        else {
            $guid = $user->getExternalGuid();
        }

        $classmates = $studentService->getClassmates($guid, $user->getTokenExternal()->getToken());

        if (!empty($classmates)) {
            $data['data']['classmates'] = $classmates;
        } else {
            $data['data']['classmates'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['show']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
