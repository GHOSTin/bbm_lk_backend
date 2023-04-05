<?php


namespace App\Service;


use App\Helper\Exception\ApiExceptionHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class RequestValidatorService
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function checkRequiredBody($data) {
        if (is_null($data)) {
            ApiExceptionHandler::errorApiHandlerRequiredBodyMessage();
        }
    }

    public function checkExistEntity($field, $class_name, $idSearch, $additionalCondition=[])
    {
        if (class_exists($class_name)) {
            if (empty($additionalCondition))
                $entity = $this->em->getRepository($class_name)->find($idSearch);
            else {
                $additionalCondition['id'] = $idSearch;
                $entity = $this->em->getRepository($class_name)->findOneBy($additionalCondition);
            }

            if (!$entity) {
                ApiExceptionHandler::errorApiHandlerObjectNotFoundMessage($field);
            }
        }
    }

    public function checkBodyForRequiredFields($data, $fields) {
        self::checkRequiredBody($data);
        $invalid_field = [];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                $invalid_field[] = $field;
            }
        }
        if (!empty($invalid_field)) {
            ApiExceptionHandler::errorApiHandlerRequiredFieldsMessage($invalid_field);
        }
    }

    public function checkAuthenticationUser($user){
        if(!$user){
            ApiExceptionHandler::errorApiHandlerMessage(
                'Authentication Required',
                'Unauthorized',
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    public function checkRequestValidationApikey ($apikey){
        if(!$apikey){
            ApiExceptionHandler::errorApiHandlerMessage(null, 'Invalid apikey parameters');
        }
    }


}