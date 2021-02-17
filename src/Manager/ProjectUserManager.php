<?php

namespace App\Manager;

use App\Entity\ProjectUser;
use App\Repository\ProjectUserRepository;

class ProjectUserManager
{
    private ProjectUserRepository $projectUserRepository;

    public function __construct(ProjectUserRepository $projectUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePermissions(ProjectUser $projectUser, array $permissions): void
    {
        $projectUser->permissions = $permissions;

        $this->projectUserRepository->save($projectUser);
    }
}
