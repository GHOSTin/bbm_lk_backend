<?php

namespace App\Controller\Api;


use App\Entity\AbstractUser;
use App\Entity\Student;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Service\DebtsService;
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
class DebtApiController extends AbstractApiController
{
    /**
     * @SWG\Tag(name="Debts")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Debts",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="debts", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=Debt::class)
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
     * @Route("/debts", name="api_debts", methods={"GET"})
     * @param DebtsService $debtsService
     * @return Response
     */
    public function getDebtsByStudent(DebtsService $debtsService): ?Response
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

        $debts = $debtsService->getDebts(
            $user->getTokenExternal()->getToken()
        );
        $data['data']['debts'] = $debts;
        $json = $this->serializer->serialize($data, 'json');
        return $this->createResponse($json, Response::HTTP_OK);
    }
}