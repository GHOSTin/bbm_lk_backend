<?php

namespace App\Service;

use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractService
{
    protected $em;
    protected $validator;
    protected $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        $this->serializer = $serializer;
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    protected function validate($object) {
        $error = $this->validator->validate($object);
        if (count($error) > 0)
            ApiExceptionHandler::errorApiHandlerValidatorMessage($error);
    }
}