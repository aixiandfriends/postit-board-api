<?php

namespace PostIt\EventListener;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class RequestEventListener
{
    public function onKernelException(GetResponseForExceptionEvent $responseForExceptionEvent)
    {
        $request = $responseForExceptionEvent->getRequest();
        if ($responseForExceptionEvent->getException() instanceof MethodNotAllowedHttpException
            && 'OPTIONS' === $request->getMethod()
        ) {
            $response = new JsonResponse(null, Response::HTTP_OK);
            $responseForExceptionEvent->setResponse($response);

            $responseForExceptionEvent->stopPropagation();
        }
    }

}
