<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects", methods={"GET"})
 */
class GetProjects
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(): array
    {
        return $this->projectRepository->findAll();
    }
}
