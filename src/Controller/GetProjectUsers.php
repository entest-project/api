<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Security\Voter\Verb;
use App\Serializer\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizations/{organizationSlug}/projects/{projectSlug}/users', requirements: ['organizationSlug' => '[0-9a-z-]+', 'projectSlug' => '[0-9a-z-]+'], methods: ['GET'])]
#[Route('/projects/{projectSlug}/users', requirements: ['projectSlug' => '[0-9a-z-]+'], methods: ['GET'])]
class GetProjectUsers extends Api
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly ProjectUserRepository $projectUserRepository
    ) {}

    public function __invoke(string $projectSlug, string $organizationSlug = null): Response
    {
        $project = $this->projectRepository->findBySlugs($projectSlug, $organizationSlug);

        if (null === $project) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::LIST_USERS, $project);

        $users = $this->projectUserRepository->findBy(['project' => $project]);

        return $this->buildSerializedResponse($users, Groups::ListProjectUsers);
    }
}
