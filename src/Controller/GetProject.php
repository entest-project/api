<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations/{organizationSlug}/projects/{projectSlug}", methods={"GET"}, requirements={"organizationSlug": "[0-9a-z-]+", "projectSlug": "[0-9a-z-]+"})
 * @Route("/projects/{projectSlug}", methods={"GET"}, requirements={"projectSlug": "[0-9a-z-]+"})
 */
class GetProject extends Api
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

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
