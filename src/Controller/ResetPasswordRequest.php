<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Mail\ResetPasswordRequestMail;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reset-password-request', methods: ['POST'])]
class ResetPasswordRequest extends Api
{
    public function __construct(
        private readonly UserManager $userManager
    ) {}

    public function __invoke(Request $request): Response
    {
        try {
            $user = $this->userManager->resetPasswordRequest($this->getFromBody('email', $request));

            $this->sendMail($user->email, new ResetPasswordRequestMail(['link' => sprintf(
                '%s/reset-password?code=%s',
                $this->getParameter('allowed_origin'),
                $user->resetPasswordCode
            )]));

            return new JsonResponse();
        } catch (UserNotFoundException $exception) {
            return new JsonResponse();
        }
    }
}
