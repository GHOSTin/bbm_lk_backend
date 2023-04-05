<?php


namespace App\Security;

use App\Entity\Device;
use App\Entity\TokenExternal;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Exception\ResponseCode;
use App\Helper\Status\DeviceStatus;
use App\Helper\Status\TokenExternalStatus;
use App\Service\ApiExternal\ProfileService;
use App\Service\ApiExternal\SecurityService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $externalSecurityService;

    public function __construct(EntityManagerInterface $em,
                                SecurityService $externalSecurityService
    )
    {
        $this->em = $em;
        $this->externalSecurityService = $externalSecurityService;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        return $request->headers->has('apikey');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->headers->get('apikey'),
        ];

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'];
        if (null === $token) {
            ApiExceptionHandler::errorApiHandlerMessage(null, 'Invalid API Token');
        }

        /** @var Device $device */
        $device = $this->em->getRepository(Device::class)->findOneBy([
            'token' => $token,
            'status' => DeviceStatus::ACTIVE,
        ]);

        if (!$device) {
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Auth Invalid Token',
                ResponseCode::HTTP_UNAUTHORIZED
            );
        }

        if ($device->getExpiredAt() <= new DateTime()) {
            $device->setStatus(DeviceStatus::EXPIRED);
            $this->em->persist($device);
            $this->em->flush();
            ApiExceptionHandler::errorApiHandlerMessage(
                null,
                'Auth Token Expired',
                ResponseCode::HTTP_UNAUTHORIZED
            );
        }

        $tokenExternal = $this->em->getRepository(TokenExternal::class)->findOneBy([
            'user' => $device->getUser(),
            'status' => TokenExternalStatus::ACTIVE,
        ]);

        if (!$tokenExternal or $tokenExternal->getExpiredAt() < new DateTime()) {
            $newTokenExternal = $this->externalSecurityService->externalLogin($device->getUser());
            $this->em->flush();
        }

        return $device->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        ApiExceptionHandler::errorApiHandlerMessage(
            null,
            $exception->getMessage(),
            ResponseCode::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        ApiExceptionHandler::errorApiHandlerMessage(
            null,
            'Authentication Required',
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    public function supportsRememberMe()
    {
        return false;
    }
}