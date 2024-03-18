<?php

namespace App\Controller;

use App\Exception\ProjectNotFoundException;
use App\Exception\UserNotFoundException;
use App\Manager\ProjectUserManager;
use App\Repository\ProjectUserRepository;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects/{projectId}/users/{userId}', requirements: ['projectId' => '[a-f0-9-]+', 'userId' => '[a-f0-9-]+'], methods: ['POST'])]
class CreateProjectUser extends Api
{
    public function __construct(
        private readonly ProjectUserManager $projectUserManager,
        private readonly ProjectUserRepository $projectUserRepository
    ) {}

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
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException();
        }
    }
}
