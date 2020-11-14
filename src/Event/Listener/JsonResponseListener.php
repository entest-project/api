<?php

namespace App\Event\Listener;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class JsonResponseListener
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if (!$result instanceof Response) {
            if (null === $result) {
                $event->setResponse(new JsonResponse(null, Response::HTTP_NO_CONTENT));
            } elseif (is_array($result)) {
                $event->setResponse(new Response(
                    $this->serializer->serialize(
                        $result,
                        'json',
                        SerializationContext::create()->setGroups(['collection'])
                    ),
                    Response::HTTP_OK,
                    [
                        'Content-type' => 'application/json'
                    ]
                ));
            } else {
                $event->setResponse(new Response(
                    $this->serializer->serialize(
                        $result,
                        'json',
                        SerializationContext::create()->setGroups(['display'])
                    ),
                    Response::HTTP_OK,
                    [
                        'Content-type' => 'application/json'
                    ]
                ));
            }
        }
    }
}
