<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations/{organizationSlug}/projects/{projectSlug}/users", methods={"GET"}, requirements={"organizationSlug": "[0-9a-z-]+", "projectSlug": "[0-9a-z-]+"})
 * @Route("/projects/{projectSlug}/users", methods={"GET"}, requirements={"projectSlug": "[0-9a-z-]+"})
 */
class GetProjectUsers extends Api
{
    private ProjectRepository $projectRepository;

    private ProjectUserRepository $projectUserRepository;

    public function __construct(ProjectRepository $projectRepository, ProjectUserRepository $projectUserRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    public function __invoke(string $projectSlug, string $organizationSlug = null): Response
    {
        $project = $this->projectRepository->findBySlugs($projectSlug, $organizationSlug);

        if (null === $project) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::LIST_USERS, $project);

        $users = $this->projectUserRepository->findBy(['project' => $project]);

        return $this->buildSerializedResponse($users, 'LIST_PROJECT_USERS');
    }
}
