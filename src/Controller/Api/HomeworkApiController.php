<?php


namespace App\Controller\Api;

use App\Entity\Student;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Mapped\Homework;
use App\Helper\Mapped\Subject;
use App\Service\HomeworkService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class HomeworkApiController
 * @package App\Controller\Api
 */
class HomeworkApiController extends AbstractApiController
{
    /**
     * @param HomeworkService $homeworkService
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/homework/send", name="api_homework_send", methods={"POST"})
     * @SWG\Tag(name="Homework")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Success sending",
     *     @SWG\Schema(
     *        type="object",
     *        @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="statusMessage", type="string")
     *        )
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
     * @SWG\Parameter(
     *   name="body",
     *   required=true,
     *   in="body",
     *   description="homework body",
     *   @SWG\Schema(
     *     @SWG\Property(property="subjectName", type="string", description="required", example="Математический анализ"),
     *     @SWG\Property(property="student", type="string", description="required", example="Иван Глушко"),
     *     @SWG\Property(property="group", type="string", description="required", example="ФДП-203"),
     *     @SWG\Property(property="date", type="string", description="required", example="2020-08-20"),
     *     @SWG\Property(property="description", type="string", description="required", example=""),
     *     @SWG\Property(property="attachments", type="array",
     *          @SWG\Items(type="string")
     *     )
     *  )
     * )
     */
    public function sendAction(HomeworkService $homeworkService, Request $request, SerializerInterface $serializer)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        /** @var Student $sudent */
        $student = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($student);
        try {
            $homework = $serializer->deserialize($request->getContent(), Homework::class, "json");
        }
        catch (\Exception $exception){
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Invalid json'
            );
        }

        $homeworkService->sendAction($homework);
        return $this->json(['data' => ['statusMessage' => 'Ok']]);
    }

    /**
     * @param HomeworkService $homeworkService
     * @Route("/homework", name="api_homework_get_content", methods={"GET"})
     * @SWG\Tag(name="Homework")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Success sending",
     *     @SWG\Schema(
     *         @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="fullName", type="string", example="Лапенко Антон Николаевич"),
     *              @SWG\Property(property="group", type="string", example="ПФК-293"),
     *              @SWG\Property(property="subjects", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=Subject::class, groups={"show"})
     *                  )
     *              ),
     *              @SWG\Property(property="maxUploadFileSize", type="integer", example=134217728),
     *         )
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
     */
    public function getHomework(HomeworkService $homeworkService, Request $request)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        $this->requestValidatorService->checkAuthenticationUser($this->getUser());
        $content = $homeworkService->getHomeworkContent($this->getUser());
        $data['data'] = $content;
        return $this->json($data);
    }
}
