<?php


namespace App\Service\ApiExternal;



use App\Entity\AbstractUser;
use App\Entity\Student;
use App\Entity\TokenExternal;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SecurityService extends ProfileService
{
    private const method_login = '/login';

    protected function makeGetRequest($method, $headers, $query, $token = null, $requestOptions = null)
    {
        $request = new Client();
        if (!is_null($token)) {
            $headers["Authorization"] = 'Bearer ' . $token;
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

    public function externalLogin(AbstractUser $user) {
        $body = $this->serializer->serialize($user, 'json', ['api' => 'external']);
        $response = $this->makePostRequest(self::method_login, [], [], $body);
        $data = $this->decodeContent($this->getContent($response));
        $code = $this->getStatusCode($response);
        if ($code == ResponseCode::HTTP_OK and array_key_exists('token', $data)) {
            return $this->refreshTokenExternal($user, $data['token']);
        }
        else {
            $detail = '';
            if (array_key_exists('message', $data))
                $detail = $data['message'];
            ApiExceptionHandler::errorApiHandlerMessage(null, $detail);
        }
        return null;
    }

    public function externalSignUp($userData) {
        $body = [
            'email' => $userData['email'],
            'gradebookId' => $userData['gradeBookId'],
            'username' => $userData['surname'],
        ];
        $response = $this->makePostRequest(self::method_login, [], [], json_encode($body, true));
        $responseContent = $this->getContent($response);
        $responseData = $this->decodeContent($responseContent);
        $code = $this->getStatusCode($response);
        if ($code == ResponseCode::HTTP_OK and
            array_key_exists('token', $responseData) and array_key_exists('status', $responseData)
        ) {
            /** @var AbstractUser $user */
            $user = $this->serializer->deserialize(
                json_encode($userData),
                Student::class,
                'json',
                [
                    'api' => 'internal',
                    'role' => $responseData['status']
                ]
            );
            $this->refreshTokenExternal($user, $responseData['token']);
            return $user;
        }
        else {
            $detail = '';
            if (array_key_exists('message', $responseData))
                $detail = $responseData['message'];
            ApiExceptionHandler::errorApiHandlerMessage(null, $detail);
        }
        return null;
    }

    private function refreshTokenExternal(AbstractUser $user, $token) {
        $tokenExternal = $user->getTokenExternal();
        if (!$tokenExternal) {
            $tokenExternal = new TokenExternal();
            $user->setTokenExternal($tokenExternal);
        }
        $tokenExternal->refreshExpiredToken($token);
        $this->em->persist($tokenExternal);
        return $tokenExternal;
    }

    private function getFullUrlMethod($method) {
        return $this->apiDomain . $method;
    }
}