<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{slug}", methods={"GET"}, requirements={"id": "[a-z-]+"})
 */
class GetProject
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(string $slug): Project
    {
        $project = $this->projectRepository->findOneBy(['slug' => $slug]);

        if ($project === null) {
            throw new NotFoundHttpException();
        }

        return $project;
    }
}
