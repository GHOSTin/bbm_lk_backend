<?php

namespace App\Controller\Api;

use App\Entity\Reference;
use App\Entity\UserReference;
use App\Helper\Exception\ApiException;
use App\Service\ReferenceService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserReferenceApiController
 * @package  App\Controller\Api
 * @Route("/user/references")
 */
class UserReferenceApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Reference")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Get UserReference",
     *     @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              ref=@Model(type=UserReference::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Reference Not Found",
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
     * @SWG\Parameter(
     *   name="body",
     *   required=true,
     *   in="body",
     *   description="email and password",
     *   @SWG\Schema(
     *     @SWG\Property(property="referenceId", type="integer", description="required"),
     *     @SWG\Property(property="dateOnReference", type="string", description="required"),
     *     @SWG\Property(property="note", type="string", description="not required"),
     *  )
     * )
     *
     * @Route("/", name="api_user_reference_new", methods={"POST"})
     * @param Request $request
     * @param ReferenceService $referenceService
     * @return Response
     */
    public function new(Request $request, ReferenceService $referenceService)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        $json = $request->getContent();
        $data = json_decode($json, true);
        $this->requestValidatorService->checkBodyForRequiredFields($data, ['referenceId', 'dateOnReference']);
        $userReference = $referenceService->createUserReference($user, $data);
        $referenceService->sendAction($userReference);
        $result['data'] = $userReference;
        $json = $this->serializer->serialize($result, 'json', ['groups' => 'show']);
        return $this->createResponse($json, Response::HTTP_CREATED);
    }

    /**
     *
     * @SWG\Tag(name="Reference")
     *
     * @SWG\Parameter(
     *   name="apikey",
     *   type="string",
     *   required=true,
     *   in="header",
     *   description="auth user's apikey"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Reference list",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="fullName", type="string"),
     *              @SWG\Property(property="group", type="string"),
     *              @SWG\Property(property="references", type="array",
     *                  @Model(type=Reference::class, groups={"show"})
     *              ),
     *              @SWG\Property(property="dateOnReferenceArray", type="array",
     *                  @SWG\Items(type="string", description="Date in ISO8601"),
     *              )
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
     * @Route("/", name="api_reference_list", methods={"GET"})
     * @param ReferenceService $referenceService
     * @return Response
     */
    public function list(ReferenceService $referenceService, Request $request)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        $data['data'] = $referenceService->getListReference($user);
        $json = $this->serializer->serialize($data, 'json', ['groups' => 'show']);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
