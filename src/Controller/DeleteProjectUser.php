<?php

namespace App\Controller;

use App\Entity\ProjectUser;
use App\Repository\ProjectUserRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{project}/users/{user}", methods={"DELETE"}, requirements={"project": "[a-f0-9-]+", "user": "[a-f0-9-]+"})
 */
class DeleteProjectUser extends Api
{
    private ProjectUserRepository $projectUserRepository;

    public function __construct(ProjectUserRepository $projectUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
    }

    public function __invoke(ProjectUser $projectUser, Request $request): Response
    {
        $this->denyAccessUnlessGranted(Verb::DELETE, $projectUser);

        try {
            $this->projectUserRepository->delete($projectUser);

            return new JsonResponse();
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
