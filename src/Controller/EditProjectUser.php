<?php

namespace App\Controller;

use App\Entity\ProjectUser;
use App\Helper\RequestHelper;
use App\Manager\ProjectUserManager;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects/{project}/users/{user}', requirements: ['project' => '[a-f0-9-]+', 'user' => '[a-f0-9-]+'], methods: ['PUT'])]
class EditProjectUser extends Api
{
    public function __construct(
        private readonly ProjectUserManager $projectUserManager
    ) {}

    public function __invoke(ProjectUser $projectUser, Request $request): Response
    {
        $this->denyAccessUnlessGranted(Verb::UPDATE, $projectUser);

        try {
            $this->projectUserManager->changePermissions($projectUser, RequestHelper::extractFromContent($request, 'permissions'));

            return $this->buildSerializedResponse($projectUser, 'READ_PROJECT_USER');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
