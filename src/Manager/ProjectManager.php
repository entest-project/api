<?php

namespace App\Manager;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;

class ProjectManager
{
    private ProjectRepository $projectRepository;

    private ProjectUserRepository $projectUserRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        ProjectUserRepository $projectUserRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createProject(Project $project, User $user): void
    {
        $this->projectRepository->save($project);
        $this->projectUserRepository->makeAdmin($user, $project);
    }
}
