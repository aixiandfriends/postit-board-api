<?php

namespace PostIt\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseEventListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'Authorization');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,OPTIONS,DELETE,PUT,PATCH,HEAD');

        if (false !== strpos($response->headers->get('Content-Type'),'application/json')) {
            $content = json_decode($response->getContent(), true);

            $response->setContent(json_encode($this->formatJsonId($content)));
        }

        if (Response::HTTP_METHOD_NOT_ALLOWED === $response->getStatusCode() && $event->getRequest()->isMethod('OPTIONS')) {
            $response->setStatusCode(Response::HTTP_OK);
        }
    }

    private function formatJsonId(array $data)
    {
        foreach ($data as $key => &$value) {
            if ('_id' === $key) {
                $value = $value['$id'];
            }

            if (is_array($value)) {
                $value = $this->formatJsonId($value);
            }
        }

        return $data;
    }
}
