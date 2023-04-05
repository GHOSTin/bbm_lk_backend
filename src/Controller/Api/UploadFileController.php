<?php


namespace App\Controller\Api;


use App\Entity\Student;
use App\Helper\Exception\ApiException;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Service\FileUploadService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploadFileController extends AbstractApiController
{
    public static  $homeworkDir = "/homework/";

    /**
     * @param FileUploadService $uploadService
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/file/upload", name="api_upload_file", methods={"POST"})
     * @SWG\Response(
     *     response="200",
     *     description="return file path on server",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="path", type="string", example="/homework/Photo1-5f3ebfe10d4bf.png"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Not Supporting File Type",
     *     @SWG\Schema(
     *          @SWG\Property(property="error", type="object",
     *              ref=@Model(type=ApiException::class, groups={"show"})
     *          )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=413,
     *     description="File Too Large",
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
     *   name="file",
     *   type="file",
     *   required=true,
     *   in="formData",
     *   description="upload file"
     * )
     *
     */
    public function sendAction(Request $request, FileUploadService $uploadService)
    {
        $apikey = $request->headers->get('apikey', null);
        $this->requestValidatorService->checkRequestValidationApikey($apikey);
        /** @var Student $sudent */
        $student = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($student);

        /** @var UploadedFile $file */
        $file = $request->files->get('file', null);
        if (is_null($file)) {
            ApiExceptionHandler::errorApiHandlerMessage(
                'File Too Large',
                'Max File Size ' . ini_get('post_max_size'),
                ResponseCode::HTTP_REQUEST_ENTITY_TOO_LARGE
            );
        }
        if (!in_array($file->getMimeType(), FileUploadService::BLOCKED_MIME)) {
            $filePath = $uploadService->upload($file, self::$homeworkDir);
        }
        else {
            ApiExceptionHandler::errorApiHandlerMessage(null, 'Not Supporting File Type');
        }
        return $this->json(['path' => $filePath]);
    }
}