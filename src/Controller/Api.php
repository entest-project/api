<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class Api extends AbstractController
{
    protected SerializerInterface $serializer;

    protected ValidatorInterface $validator;

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

    /**
     * @throws BadRequestHttpException
     */
    protected function validate($entity): void
    {
        $errors = $this->validator->validate($entity);

        if ($errors->count() > 0) {
            throw new BadRequestHttpException();
        }
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}
