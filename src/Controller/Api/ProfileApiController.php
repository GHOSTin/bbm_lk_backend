<?php

namespace App\Controller\Api;

use App\Entity\AbstractUser;
use App\Entity\Reference;
use App\Entity\Student;
use App\Entity\UserReference;
use App\Helper\Exception\ApiException;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Mapped\UserInterests;
use App\Service\ProfileService;
use App\Service\ReferenceService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileApiController
 * @package  App\Controller\Api
 */
class ProfileApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Profile")
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
     *     description="Get Profile",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="user", type="object",
     *                  @SWG\Property(property="avatarImageUrl", type="string", description="Avatar's user (Avalaible all)"),
     *                  @SWG\Property(property="fullName", type="string", description="fFull Name user (Avalaible all)"),
     *                  @SWG\Property(property="email", type="string", description="email user (Avalaible all)"),
     *                  @SWG\Property(property="externalGuid", type="string", description="ExternalGuid user (Avalaible all)"),
     *                  @SWG\Property(property="phone", type="string", description="phone user (Avalaible all)"),
     *                  @SWG\Property(property="birthday", type="string", description="birthday user (Format ISO8601) (Avalaible all)"),
     *                  @SWG\Property(property="age", type="integer", description="age user (Avalaible all)"),
     *                  @SWG\Property(property="city", type="string", description="City user (Avalaible all)"),
     *                  @SWG\Property(property="role", type="integer", description="role user (Avalaible all) (1 - Student, 2 - Parent, 3 - Teacher)"),
     *                  @SWG\Property(property="fullNameParent", type="string", description="fule Name Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="parentId", type="string", description="Id Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="fullNameStudent", type="string", description="Full Name student (Avalaible student and Parent)"),
     *                  @SWG\Property(property="group", type="string", description="group (Avalaible student and Parent)"),
     *                  @SWG\Property(property="speciality", type="string", description="Speciality (Avalaible student)"),
     *                  @SWG\Property(property="trainingPeriod", type="string", description="Training Period (Avalaible student)"),
     *                  @SWG\Property(property="trainingStartYear", type="string", description="Training Start Year (Avalaible student)"),
     *                  @SWG\Property(property="trainingEndYear", type="string", description="Training End Year (Avalaible student)"),
     *                  @SWG\Property(property="course", type="string", description="Course (Avalaible student)"),
     *                  @SWG\Property(property="studentId", type="string", description="studentId (Avalaible Parent)"),
     *                  @SWG\Property(property="level", type="string", description="level (Avalaible Parent)"),
     *                  @SWG\Property(property="position", type="string", description="Position (Avalaible Teacher)"),
     *                  @SWG\Property(property="nameSubject", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="firstName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="lastName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="experience", type="integer", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="interests", type="array", description="(Avalaible All)",
     *                      @SWG\Items(ref=@Model(type=UserInterests::class)),
     *                  ),
     *                  @SWG\Property(property="myInterests", type="array",
     *                      @SWG\Items(type="string")
     *                  )
     *              )
     *          )
     *     )
     * )
     *
     * @Route("/my/profile", name="api_my_profile", methods={"GET"})
     * @param ProfileService $profileService
     * @return Response
     */
    public function myProfile(ProfileService $profileService, Request $request)
    {
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);

        $profile = $profileService->getMyProfile($user);
        $data['data']['user'] = $profile;

        /** @var AbstractUser $userInDb */
        $userInDb = $this->em->getRepository(AbstractUser::class)
            ->findBy(['email' => $user->getUsername()]);
        $json = $this->serializer->serialize(
            $data,
            'json',
            ['api' => 'internal', 'myInterests' => array_shift($userInDb)->getMyInterests()]
        );
        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Profile")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Profile",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="user", type="object",
     *                  @SWG\Property(property="avatarImageUrl", type="string", description="Avatar's user (Avalaible all)"),
     *                  @SWG\Property(property="fullName", type="string", description="fFull Name user (Avalaible all)"),
     *                  @SWG\Property(property="email", type="string", description="email user (Avalaible all)"),
     *                  @SWG\Property(property="externalGuid", type="string", description="ExternalGuid user (Avalaible all)"),
     *                  @SWG\Property(property="phone", type="string", description="phone user (Avalaible all)"),
     *                  @SWG\Property(property="birthday", type="string", description="birthday user (Format ISO8601) (Avalaible all)"),
     *                  @SWG\Property(property="age", type="integer", description="age user (Avalaible all)"),
     *                  @SWG\Property(property="city", type="string", description="City user (Avalaible all)"),
     *                  @SWG\Property(property="role", type="integer", description="role user (Avalaible all) (1 - Student, 2 - Parent, 3 - Teacher)"),
     *                  @SWG\Property(property="fullNameParent", type="string", description="fule Name Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="parentId", type="string", description="Id Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="fullNameStudent", type="string", description="Full Name student (Avalaible student and Parent)"),
     *                  @SWG\Property(property="group", type="string", description="group (Avalaible student and Parent)"),
     *                  @SWG\Property(property="speciality", type="string", description="Speciality (Avalaible student)"),
     *                  @SWG\Property(property="trainingPeriod", type="string", description="Training Period (Avalaible student)"),
     *                  @SWG\Property(property="trainingStartYear", type="string", description="Training Start Year (Avalaible student)"),
     *                  @SWG\Property(property="trainingEndYear", type="string", description="Training End Year (Avalaible student)"),
     *                  @SWG\Property(property="course", type="string", description="Course (Avalaible student)"),
     *                  @SWG\Property(property="studentId", type="string", description="studentId (Avalaible Parent)"),
     *                  @SWG\Property(property="level", type="string", description="level (Avalaible Parent)"),
     *                  @SWG\Property(property="position", type="string", description="Position (Avalaible Teacher)"),
     *                  @SWG\Property(property="nameSubject", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="firstName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="lastName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="experience", type="integer", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="interests", type="array", description="(Avalaible All)",
     *                      @SWG\Items(ref=@Model(type=UserInterests::class)),
     *                  ),
     *                  @SWG\Property(property="myInterests", type="array",
     *                      @SWG\Items(type="string")
     *                  )
     *              )
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
     *   description="Update user's phone",
     *   @SWG\Schema(
     *     @SWG\Property(property="phone", type="string", description="required", example="9123456789"),
     *     @SWG\Property(property="myInterests", type="array",
     *          @SWG\Items(type="string")
     *     )
     *  )
     * )
     *
     * @Route("/my/profile", name="api_my_update_profile", methods={"POST"})
     * @param Request $request
     * @param ProfileService $profileService
     * @return Response
     */
    public function updateProfile(Request $request, ProfileService $profileService)
    {
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        $json = $request->getContent();
        $data = json_decode($json, true);
        $profileService->updateProfile($user, $data);
        $profile = $profileService->getMyProfile($user);
        $data['data']['user'] = $profile;

        $userInDb = $this->em->getRepository(AbstractUser::class)
            ->findBy(['email' => $user->getUsername()]);
        $json = $this->serializer->serialize(
            $data,
            'json',
            ['api' => 'internal', 'myInterests' => array_shift($userInDb)->getMyInterests()]
        );
        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Profile")
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
     *   name="externalGuid",
     *   type="string",
     *   required=true,
     *   in="query",
     *   description="External Guid User"
     * )
     *
     * @SWG\Parameter(
     *   name="role",
     *   type="integer",
     *   required=true,
     *   in="query",
     *   description="Role User (1 - Student, 2 - Parent, 3 - Teacher)"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Profile",
     *     @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="user", type="object",
     *                  @SWG\Property(property="avatarImageUrl", type="string", description="Avatar's user (Avalaible all)"),
     *                  @SWG\Property(property="fullName", type="string", description="fFull Name user (Avalaible all)"),
     *                  @SWG\Property(property="email", type="string", description="email user (Avalaible all)"),
     *                  @SWG\Property(property="externalGuid", type="string", description="ExternalGuid user (Avalaible all)"),
     *                  @SWG\Property(property="phone", type="string", description="phone user (Avalaible all)"),
     *                  @SWG\Property(property="birthday", type="string", description="birthday user (Format ISO8601) (Avalaible all)"),
     *                  @SWG\Property(property="age", type="integer", description="age user (Avalaible all)"),
     *                  @SWG\Property(property="city", type="string", description="City user (Avalaible all)"),
     *                  @SWG\Property(property="role", type="integer", description="role user (Avalaible all) (1 - Student, 2 - Parent, 3 - Teacher)"),
     *                  @SWG\Property(property="fullNameParent", type="string", description="fule Name Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="parentId", type="string", description="Id Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="fullNameStudent", type="string", description="Full Name student (Avalaible student and Parent)"),
     *                  @SWG\Property(property="group", type="string", description="group (Avalaible student and Parent)"),
     *                  @SWG\Property(property="speciality", type="string", description="Speciality (Avalaible student)"),
     *                  @SWG\Property(property="trainingPeriod", type="string", description="Training Period (Avalaible student)"),
     *                  @SWG\Property(property="trainingStartYear", type="string", description="Training Start Year (Avalaible student)"),
     *                  @SWG\Property(property="trainingEndYear", type="string", description="Training End Year (Avalaible student)"),
     *                  @SWG\Property(property="course", type="string", description="Course (Avalaible student)"),
     *                  @SWG\Property(property="studentId", type="string", description="studentId (Avalaible Parent)"),
     *                  @SWG\Property(property="level", type="string", description="level (Avalaible Parent)"),
     *                  @SWG\Property(property="position", type="string", description="Position (Avalaible Teacher)"),
     *                  @SWG\Property(property="nameSubject", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="firstName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="lastName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="experience", type="integer", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="interests", type="array", description="(Avalaible All)",
     *                      @SWG\Items(ref=@Model(type=UserInterests::class)),
     *                  ),
     *                  @SWG\Property(property="myInterests", type="array",
     *                      @SWG\Items(type="string")
     *                  )
     *              )
     *          )
     *     )
     * )
     *
     * @Route("/user/profile", name="api_user_profile", methods={"GET"})
     * @param ProfileService $profileService
     * @return Response
     */
    public function getProfileUser(Request $request, ProfileService $profileService)
    {
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        $externalGuid = $request->get('externalGuid', null);
        $role = (integer)$request->get('role', null);
        if (!$externalGuid)
            ApiExceptionHandler::errorApiHandlerRequiredFieldsMessage('externalGuid');
        if (!$role)
            ApiExceptionHandler::errorApiHandlerRequiredFieldsMessage('role');
        $profile = $profileService->getProfile($externalGuid, $role, $user->getTokenExternal()->getToken());
        $data['data']['user'] = $profile;
        $userInDb = $this->em->getRepository(AbstractUser::class)
            ->findBy(['email' => $user->getUsername()]);
        $json = $this->serializer->serialize($data, 'json',
            ['api' => 'internal', 'myInterests' => array_shift($userInDb)->getMyInterests()]);

        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Profile")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Profile",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="user", type="object",
     *                  @SWG\Property(property="avatarImageUrl", type="string", description="Avatar's user (Avalaible all)"),
     *                  @SWG\Property(property="fullName", type="string", description="fFull Name user (Avalaible all)"),
     *                  @SWG\Property(property="email", type="string", description="email user (Avalaible all)"),
     *                  @SWG\Property(property="externalGuid", type="string", description="ExternalGuid user (Avalaible all)"),
     *                  @SWG\Property(property="phone", type="string", description="phone user (Avalaible all)"),
     *                  @SWG\Property(property="birthday", type="string", description="birthday user (Format ISO8601) (Avalaible all)"),
     *                  @SWG\Property(property="age", type="integer", description="age user (Avalaible all)"),
     *                  @SWG\Property(property="city", type="string", description="City user (Avalaible all)"),
     *                  @SWG\Property(property="role", type="integer", description="role user (Avalaible all) (1 - Student, 2 - Parent, 3 - Teacher)"),
     *                  @SWG\Property(property="fullNameParent", type="string", description="fule Name Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="parentId", type="string", description="Id Parent's student (Avalaible only student)"),
     *                  @SWG\Property(property="fullNameStudent", type="string", description="Full Name student (Avalaible student and Parent)"),
     *                  @SWG\Property(property="group", type="string", description="group (Avalaible student and Parent)"),
     *                  @SWG\Property(property="speciality", type="string", description="Speciality (Avalaible student)"),
     *                  @SWG\Property(property="trainingPeriod", type="string", description="Training Period (Avalaible student)"),
     *                  @SWG\Property(property="trainingStartYear", type="string", description="Training Start Year (Avalaible student)"),
     *                  @SWG\Property(property="trainingEndYear", type="string", description="Training End Year (Avalaible student)"),
     *                  @SWG\Property(property="course", type="string", description="Course (Avalaible student)"),
     *                  @SWG\Property(property="studentId", type="string", description="studentId (Avalaible Parent)"),
     *                  @SWG\Property(property="level", type="string", description="level (Avalaible Parent)"),
     *                  @SWG\Property(property="position", type="string", description="Position (Avalaible Teacher)"),
     *                  @SWG\Property(property="nameSubject", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="firstName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="lastName", type="string", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="experience", type="integer", description="(Avalaible Teacher)"),
     *                  @SWG\Property(property="interests", type="array", description="(Avalaible All)",
     *                      @SWG\Items(ref=@Model(type=UserInterests::class)),
     *                  ),
     *                  @SWG\Property(property="myInterests", type="array",
     *                      @SWG\Items(type="string")
     *                  )
     *              )
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
     *   name="avatar",
     *   required=true,
     *   in="formData",
     *   type="file",
     *   description="Avatar's user"
     * )
     *
     * @Route("/my/profile/avatar", name="api_my_profile_avatar", methods={"POST"})
     * @param Request $request
     * @param ProfileService $profileService
     * @return Response
     */
    public function updateAvatarUser(Request $request, ProfileService $profileService)
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);
        $avatar = $request->files->get('avatar', null);
        if (is_null($avatar)) {
            ApiExceptionHandler::errorApiHandlerMessage('Missing required file: avatar');
        }
        $profileService->uploadAvatar($avatar, $user);
        $profile = $profileService->getMyProfile($user);
        $data['data']['user'] = $profile;
        $userInDb = $this->em->getRepository(AbstractUser::class)
            ->findBy(['email' => $user->getUsername()]);
        $json = $this->serializer->serialize($data, 'json',
            ['api' => 'internal', 'myInterests' => array_shift($userInDb)->getMyInterests()]);
        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Profile")
     *
     * @SWG\Response(
     *     response=200,
     *     description="info msg",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="msg", type="string")
     *          )
     *      )
     * )
     * @SWG\Parameter(
     *   name="apikey",
     *   type="string",
     *   required=true,
     *   in="header",
     *   description="auth user's apikey"
     * )
     * @Route("/my/profile/avatar", name="api_my_profile_avatar_remove", methods={"DELETE"})
     */
    public function removeAvatar(Request $request)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);

        $data['data']['msg'] = "Error";
        try {
            /** @var AbstractUser $user */
            $user = $this->getUser();
            $this->requestValidatorService->checkAuthenticationUser($user);
            $user->setAvatarImageUrl(null);
            $this->em->flush();
            $data['data']['msg'] = "Success";
        } finally {
            $json = $this->serializer->serialize($data, "json");
            return $this->createResponse($json);
        }
    }
}
