<?php

namespace App\Service\ApiExternal;

use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractService
{
    protected $em;
    protected $validator;
    protected $serializer;
    protected $apiDomain;
    protected $apiVersion;

    /** @var string */
    private $token;

    public function __construct(
        $apiExternalDomain,
        $apiVersion,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        $this->apiDomain = $apiExternalDomain;
        $this->apiVersion = $apiVersion;
        $this->serializer = $serializer;
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    protected function guzzleExceptionHandler(GuzzleException $e) {
        $responseError = $this->getContent($e->getResponse());
        $responseErrorJson = $this->decodeContent($responseError);
        $responseErrorMessage = is_array($responseErrorJson) && array_key_exists('message', $responseErrorJson) ?
            $responseErrorJson['message'] :
            $responseError;
        if ($e->getCode() == ResponseCode::HTTP_FORBIDDEN) {
            $responseError = $this->decodeContent($this->getContent($e->getResponse()));
            ApiExceptionHandler::errorApiHandlerMessage(null,
                $responseErrorMessage,
                ResponseCode::HTTP_FORBIDDEN
            );
        }
        ApiExceptionHandler::errorApiHandlerMessage(null,
            $responseErrorMessage,
            ResponseCode::HTTP_EXTERNAL_API_ERROR
        );
    }

    protected function makeGetRequest($method, $headers, $query, $token = null, $requestOptions = null)
    {
        $request = new Client();
        if (!is_null($token)) {
            $headers["Authorization"] = 'Bearer ' . $token;
        } else {
            $headers["Authorization"] = 'Bearer ' . $this->token;
        }
        try {
            $response = $request->get($this->getFullUrlMethod($method), [
                'headers' => $headers,
                'query' => $query,
            ]);
        } catch (GuzzleException $e) {
            $this->guzzleExceptionHandler($e);
        }
        return $response;
    }

    protected function makePostRequest($method, $headers, $query, $body, $token = null, $requestOptions = null)
    {
        $request = new Client();
        if (!is_null($token)) {
            $headers["Authorization"] = 'Bearer ' . $token;
        }
        try {
            $response = $request->post($this->getFullUrlMethod($method), [
                'headers' => $headers,
                'query' => $query,
                'json' => json_decode($body),
            ]);
        } catch (GuzzleException $e) {
            $this->guzzleExceptionHandler($e);
        }
        return $response;
    }

    protected function getStatusCode(\GuzzleHttp\Psr7\Response $response) {
        return $response->getStatusCode();
    }

    protected function getContent(\GuzzleHttp\Psr7\Response $response) {
        return $response->getBody()->getContents();
    }

    protected function decodeContent($content) {
        return json_decode($content, true);
    }

    private function getFullUrlMethod($method) {
        return $this->apiDomain . $this->apiVersion . $method;
    }

    protected function validate($object) {
        $error = $this->validator->validate($object);
        if (count($error) > 0)
            ApiExceptionHandler::errorApiHandlerValidatorMessage($error);
    }

    protected function setToken($token)
    {
        $this->token = $token;
    }
}