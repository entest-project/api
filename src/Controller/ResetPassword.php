<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Mail\ResetPasswordMail;
use App\Manager\UserManager;
use App\Model\Request\ResetPasswordRequestModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reset-password", methods={"POST"})
 */
class ResetPassword extends Api
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function __invoke(Request $request): Response
    {
        /** @var ResetPasswordRequestModel $model */
        $model = ResetPasswordRequestModel::fromRequest($request);
        $this->validate($model);

        try {
            $user = $this->userManager->resetPassword($model->code, $model->newPassword);

            $this->sendMail($user->email, new ResetPasswordMail());

            return new JsonResponse();
        } catch (UserNotFoundException $exception) {
            throw new NotFoundHttpException();
        }
    }
}
