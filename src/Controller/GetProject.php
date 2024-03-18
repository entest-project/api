<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizations/{organizationSlug}/projects/{projectSlug}', requirements: ['organizationSlug' => '[0-9a-z-]+', 'projectSlug' => '[0-9a-z-]+'], methods: ['GET'])]
#[Route('/projects/{projectSlug}', requirements: ['projectSlug' => '[0-9a-z-]+'], methods: ['GET'])]
class GetProject extends Api
{
    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {}

    public function __invoke(string $projectSlug, string $organizationSlug = null): Response
    {
        $project = $this->projectRepository->findBySlugs($projectSlug, $organizationSlug);

        if (null === $project) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::READ, $project);

        return $this->buildSerializedResponse($project, 'READ_PROJECT');
    }
}
