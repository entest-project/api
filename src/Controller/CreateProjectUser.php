<?php

namespace App\Controller;

use App\Exception\ProjectNotFoundException;
use App\Exception\UserNotFoundException;
use App\Manager\ProjectUserManager;
use App\Repository\ProjectUserRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{projectId}/users/{userId}", methods={"POST"}, requirements={"projectId": "[a-f0-9-]+", "userId": "[a-f0-9-]+"})
 */
class CreateProjectUser extends Api
{
    private ProjectUserManager $projectUserManager;

    private ProjectUserRepository $projectUserRepository;

    public function __construct(ProjectUserManager $projectUserManager, ProjectUserRepository $projectUserRepository)
    {
        $this->projectUserManager = $projectUserManager;
        $this->projectUserRepository = $projectUserRepository;
    }

    public function __invoke(string $projectId, string $userId): Response
    {
        try {
            $projectUser = $this->projectUserManager->build($projectId, $userId);
            $this->denyAccessUnlessGranted(Verb::CREATE, $projectUser);

            $this->projectUserRepository->save($projectUser);

            return $this->buildSerializedResponse($projectUser, 'READ_PROJECT_USER');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ProjectNotFoundException | UserNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
