<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects', methods: ['GET'])]
class GetProjects extends Api
{
    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {}

    public function __invoke(): Response
    {
        return $this->buildSerializedResponse(
            $this->projectRepository->findProjectsForUser($this->getUser()),
            'LIST_PROJECTS'
        );
    }
}
