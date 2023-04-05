<?php

namespace App\Controller\Api;

use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Teacher;
use App\Helper\Mapped\Subject;
use App\Helper\Mapped\Lesson;
use App\Helper\Mapped\Schedule;
use App\Service\SubjectService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubjectApiController
 * @package  App\Controller\Api
 */
class SubjectApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Subject")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Subject",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="subjects", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=Subject::class, groups={"show"})
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
     * @Route("/subjects", name="api_subjects", methods={"GET"})
     * @param Request $request
     * @param SubjectService $subjectService
     * @return Response
     */
    public function getSubjects(Request $request, SubjectService $subjectService)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        if ($user instanceof ParentStudent) {
            $guid = $user->getStudentExternalId();
        } else {
            $guid = $user->getExternalGuid();
        }

        if ($user instanceof Teacher) {
            $subjects = $subjectService->getSubjectsTeacher($guid, $user->getTokenExternal()->getToken());
        }
        else {
            $subjects = $subjectService->getSubjectsStudent($guid, $user->getTokenExternal()->getToken());
        }
        if (!empty($subjects)) {
            $data['data']['subjects'] = $subjects;
            $data['data']['semester'] = $subjects[0]->getSemester();
        } else {
            $data['data']['subjects'] = [];
        }

        $json = $this->serializer->serialize($data, 'json');
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
