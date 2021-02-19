<?php

namespace App\Controller;

use App\Entity\ProjectUser;
use App\Manager\ProjectUserManager;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{project}/users/{user}/token", methods={"GET"}, requirements={"project": "[0-9a-f-]+", "user": "[0-9a-f-]+"})
 */
class GetProjectUserToken extends Api
{
    private ProjectUserManager $projectUserManager;

    public function __construct(ProjectUserManager $projectUserManager)
    {
        $this->projectUserManager = $projectUserManager;
    }

    public function __invoke(ProjectUser $projectUser): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ_TOKEN, $projectUser);

        if (!$projectUser->token) {
            try {
                $this->projectUserManager->buildToken($projectUser);
            } catch (ORMException | OptimisticLockException $e) {
                throw new UnprocessableEntityHttpException();
            }
        }

        return $this->buildSerializedResponse([
            'token' => $projectUser->token
        ], 'READ_PROJECT_USER_TOKEN');
    }
}
