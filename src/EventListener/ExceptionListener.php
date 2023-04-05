<?php


namespace App\EventListener;



use App\Helper\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        if (!$exception instanceof ApiException) {
            return;
        }
        $customResponse = new JsonResponse($exception->responseBody(),
            $exception->getStatusCode());
        $event->setResponse($customResponse);
    }

}