<?php

namespace App\Model\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class AbstractRequestModel implements RequestModel
{
    public static function fromRequest(Request $request): self
    {
        $properties = (new \ReflectionClass(static::class))->getProperties();
        $model = new static();
        $requestContent = json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException();
        }

        foreach ($properties as $property) {
            if (!$property->isPublic()) {
                continue;
            }

            $model->{$property->getName()} = ($requestContent[$property->getName()] ?? null);
        }

        return $model;
    }
}
