<?php

namespace PostIt\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseEventListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->set('Access-Control-Allow-Origin', $event->getRequest()->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Methods', $event->getRequest()->getMethod());
    }
}
