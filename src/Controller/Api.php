<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class Api extends AbstractController
{
    protected SerializerInterface $serializer;

    protected function buildSerializedResponse($data, string $group = null, int $statusCode = Response::HTTP_OK): Response
    {
        return new Response(
            $this->serializer->serialize(
                $data,
                'json',
                $group ? SerializationContext::create()->setGroups([$group]) : null
            ),
            $statusCode,
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }
}
