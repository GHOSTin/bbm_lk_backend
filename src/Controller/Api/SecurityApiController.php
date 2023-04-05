<?php

namespace App\Controller\Api;

use App\Entity\Device;
use App\Helper\Exception\ApiException;
use App\Helper\Mapped\ParentStudent;
use App\Helper\Mapped\Student;
use App\Service\ApiExternal\ProfileService;
use App\Service\SecurityService;
use DateTime;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityApiController
 * @package App\Controller\Api
 * @Route("/security")
 */
class SecurityApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Security")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get device",
     *     @SWG\Schema(
     *          ref=@Model(type=Device::class, groups={"login_show"})
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
     *     response=401,
     *     description="Email or Password incorrect",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Parameter(
     *   name="body",
     *   required=true,
     *   in="body",
     *   description="email and password",
     *   @SWG\Schema(
     *     @SWG\Property(property="email", type="string", description="required"),
     *     @SWG\Property(property="password", type="string", description="required"),
     *  )
     * )
     *
     * @Route("/login", name="api_security_login", methods={"POST"})
     * @param Request $request
     * @param SecurityService $securityService
     * @param ProfileService $profileService
     * @param \App\Service\ApiExternal\SecurityService $externalSecurityService
     * @return Response
     */
    public function login(
        Request $request,
        SecurityService $securityService,
        ProfileService $profileService,
        \App\Service\ApiExternal\SecurityService $externalSecurityService
    )
    {
        $data = json_decode($request->getContent(), true);
        $this->requestValidatorService->checkBodyForRequiredFields($data, ['email', 'password']);
        $device = $securityService->getUserForLogin($data['email'], $data['password']);
        $tokenExternal = $device->getUser()->getTokenExternal();
        if (!$tokenExternal or $tokenExternal->getExpiredAt() < new DateTime()) {
            $newTokenExternal = $externalSecurityService->externalLogin($device->getUser());
            $this->em->flush();
        }
        $profile = $profileService->getMyProfile($device->getUser());
        if ($profile instanceof Student or $profile instanceof ParentStudent) {
            $device->getUser()->setGroup($profile->getGroup());
        }
        $json = $this->serializer->serialize($device, 'json', ['groups' => 'login_show']);
        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Security")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Log out",
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Token not found or Expired",
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
     * @Route("/logout", name="api_security_logout", methods={"GET"})
     */
    public function logout(Request $request, SecurityService $securityService)
    {
        $token = $request->headers->get('apikey');
        $securityService->logoutToken($token);
        return $this->createResponse('', Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Security")
     *
     * @SWG\Response(
     *     response=201,
     *     description="User created and send email",
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
     *     response=403,
     *     description="Username or gradeBookId incorrect",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=461,
     *     description="Email or user already exist",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=462,
     *     description="External Api Error",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=463,
     *     description="Validation Error",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Parameter(
     *   name="body",
     *   required=true,
     *   in="body",
     *   description="sign up (Roles: 1 - Student, 2 - Parent, 3 - Teacher)",
     *   @SWG\Schema(
     *     @SWG\Property(property="email", type="string", description="required"),
     *     @SWG\Property(property="gradeBookId", type="integer", description="required"),
     *     @SWG\Property(property="surname", type="string", description="required"),
     *  )
     * )
     *
     * @Route("/signup", name="api_security_signup", methods={"POST"})
     * @param Request $request
     * @param SecurityService $securityService
     * @param ProfileService $profileService
     * @return Response
     */
    public function signUp(Request $request, SecurityService $securityService, ProfileService $profileService)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);
        $this->requestValidatorService->checkBodyForRequiredFields($data, ['email', 'gradeBookId', 'surname']);
        $user = $securityService->signUp($data);
        $profile = $profileService->getMyProfile($user);
        return $this->createResponse('', Response::HTTP_CREATED);
    }

    /**
     *
     * @SWG\Tag(name="Security")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User created and send email",
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
     *     description="User not Found",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=429,
     *     description="Too Many Request Reset Password",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Parameter(
     *   name="body",
     *   required=true,
     *   in="body",
     *   description="",
     *   @SWG\Schema(
     *     @SWG\Property(property="email", type="string", description="required"),
     *  )
     * )
     *
     * @Route("/reset-password", name="api_security_reset_password", methods={"POST"})
     */
    public function resetPassword(Request $request, SecurityService $securityService)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);
        $this->requestValidatorService->checkBodyForRequiredFields($data, ['email']);
        $securityService->resetPasswordGenerateToken($data['email']);
        return $this->createResponse('', Response::HTTP_OK);
    }
}
