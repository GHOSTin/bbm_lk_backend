<?php

namespace App\Controller\Api;

use App\Helper\Mapped\UserInterests;
use App\Service\InterestsService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InterestsApiController
 * @package  App\Controller\Api
 */
class InterestsApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Interests")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Interests List",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="interests", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=UserInterests::class, groups={"show"})
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
     * @Route("/interests/student", name="api_interests_studen_list", methods={"GET"})
     * @param InterestsService $interestsService
     * @return Response
     */
    public function getStudentInterests(InterestsService $interestsService)
    {
        $interests = $interestsService->getStudentInterestsList();
        if (!empty($interests)) {
            $data['data']['interests'] = $interests;
        } else {
            $data['data']['interests'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['show']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
