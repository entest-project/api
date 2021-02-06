<?php

namespace App\Manager;

use App\Entity\Project;
use App\Entity\User;
use App\Exception\UserNotAllowedToCreateProjectException;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Security\OrganizationPermission;

class ProjectManager
{
    private OrganizationUserRepository $organizationUserRepository;

    private ProjectRepository $projectRepository;

    private ProjectUserRepository $projectUserRepository;

    public function __construct(
        OrganizationUserRepository $organizationUserRepository,
        ProjectRepository $projectRepository,
        ProjectUserRepository $projectUserRepository
    ) {
        $this->organizationUserRepository = $organizationUserRepository;
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    /**
     * @throws UserNotAllowedToCreateProjectException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createProject(Project $project, User $user): void
    {
        if (!$this->isAllowedToCreateProject($project, $user)) {
            throw new UserNotAllowedToCreateProjectException();
        }

        $this->projectRepository->save($project);
        $this->projectUserRepository->makeAdmin($user, $project);
    }

    private function isAllowedToCreateProject(Project $project, User $user): bool
    {
        if (null === $project->organization) {
            return true;
        }

        $organizationUser = $this->organizationUserRepository->findOneByUserAndOrganization($user, $project->organization);

        if (null === $organizationUser) {
            return false;
        }

        return count(array_intersect($organizationUser->permissions, [OrganizationPermission::ADMIN, OrganizationPermission::PROJECT_CREATE])) > 0;
    }
}
