<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects", methods={"GET"})
 */
class GetProjects extends Api
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(): Response
    {
        return $this->buildSerializedResponse(
            $this->projectRepository->findProjectsForUser($this->getUser()),
            'LIST_PROJECTS'
        );
    }
}
