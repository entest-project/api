<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Mail\ResetPasswordRequestMail;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reset-password-request", methods={"POST"})
 */
class ResetPasswordRequest extends Api
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $user = $this->userManager->resetPasswordRequest($request->get('email'));

            $this->sendMail($user->email, new ResetPasswordRequestMail(['link' => sprintf(
                '%s/reset-password?code=%s',
                $this->getParameter('%env(ALLOWED_ORIGIN)%'),
                $user->resetPasswordCode
            )]));

            return new JsonResponse();
        } catch (UserNotFoundException $exception) {
            return new JsonResponse();
        }
    }
}
