<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/login", methods={"POST"})
 */
class Login
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse();
    }
}
