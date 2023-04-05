<?php


namespace App\Service;


use App\Entity\AbstractUser;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Role\AbstractUserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileService extends AbstractService
{
    /**
     * @var ApiExternal\ProfileService
     */
    protected $externalProfileService;

    /**
     * @var FileUploadService
     */
    protected $fileUploadService;

    /**
     * @var ContainerInterface 
     */
    protected $container;

    public const AVAILABLE_AVATAR_EXTENSIONS = [
        'image/jpg',
        'image/jpeg',
        'image/png',
    ];

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ApiExternal\ProfileService $profileService,
        FileUploadService $fileUploadService,
        ContainerInterface $container
    )
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->externalProfileService = $profileService;
        $this->fileUploadService = $fileUploadService;
        $this->container = $container;
    }

    public function getProfile($guid, $role, $token) {
        switch ($role) {
            case AbstractUserRole::ROLE_STUDENT:
                $profile = $this->externalProfileService->getStudentProfile(
                    $guid,
                    $token
                );
                break;
            case AbstractUserRole::ROLE_TEACHER:
                $profile = $this->externalProfileService->getTeacherProfileByGuid(
                    $guid,
                    $token
                );
                break;
            case AbstractUserRole::ROLE_PARENT:
            default:
                $profile = null;
        }
        return $profile;
    }

    public function getMyProfile($user) {
        return $this->externalProfileService->getMyProfile($user);
    }

    public function updateProfile(AbstractUser $user, $data) {
        if (array_key_exists('phone', $data) and $data['phone']) {
            $user->setPhone($data['phone']);
        }
        if(array_key_exists('myInterests', $data)) {
            $user->setMyInterests($data['myInterests']);
        }
        $this->em->flush();
    }

    public function uploadAvatar(UploadedFile $file, AbstractUser $user) {
        $targetDirectory = $this->container->getParameter('path_avatar_directory') . $user->getId() . '/';
        try {
            $fileExtension = $file->getMimeType();
            if (!in_array($fileExtension, self::AVAILABLE_AVATAR_EXTENSIONS)) {
                throw new InvalidArgumentException();
            }
        }
        catch (InvalidArgumentException $exception) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Not support format photo',
                ResponseCode::HTTP_VALIDATION_ERROR
            );
        }
        $fileUrl = $this->fileUploadService->upload($file, $targetDirectory);
//        if ($user->getAvatarImageUrl()) {
//            $this->fileUploadService->deleteFile($user->getAvatarImageUrl());
//        }
        $user->setAvatarImageUrl($fileUrl);
        $this->em->flush();
    }

    static function getAge($birthdayDateTime) {
        if ($birthdayDateTime) {
            $todayDateTime = new \DateTime('now', new \DateTimeZone('UTC'));
            return $birthdayDateTime->diff($todayDateTime)->y;
        }
        return null;
    }
}