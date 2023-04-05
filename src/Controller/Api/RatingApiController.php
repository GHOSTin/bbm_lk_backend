<?php


namespace App\Controller\Api;

use App\Entity\AbstractUser;
use App\Entity\Student;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Service\DebtsService;
use App\Service\RatingService;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Helper\Mapped\Debt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\Exception\ApiException;

/**
 * Class SubjectApiController
 * @package  App\Controller\Api
 */
class RatingApiController extends AbstractApiController
{
    /**
     * @SWG\Tag(name="Rating")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Debts",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="student_id", type="string"),
     *              @SWG\Property(property="period", type="string"),
     *              @SWG\Property(property="rating", type="array",
     *                  @SWG\Items(
     *                       @SWG\Property(property="type", type="string"),
     *                       @SWG\Property(property="points", type="string"),
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden Access (Only Students)",
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
     * @Route("/rating", name="api_reting", methods={"GET"})
     * @param RatingService $ratingService
     * @return Response
     */
    public function getRatingByStudent(RatingService $ratingService): ?Response
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        if (!$user instanceof Student) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Доступ запрещен',
                ResponseCode::HTTP_FORBIDDEN
            );
        }

        $rating = $ratingService->getRating(
            $user->getTokenExternal()->getToken(),
            $user->getExternalGuid()
        );
        $data["data"] = $rating;
        $json = $this->serializer->serialize($data, 'json');
        return $this->createResponse($json, Response::HTTP_OK);
    }
}