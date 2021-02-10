<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use App\Security\OrganizationPermission;
use App\Security\ProjectPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait WriteProjectVoterTrait
{
    private OrganizationUserRepository $organizationUserRepository;

    private ProjectUserRepository $projectUserRepository;

    private function isAllowedToWriteProject(TokenInterface $token, Project $project): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $projectUser = $this->projectUserRepository->findOneByUserAndProject($user, $project);

        if (null !== $projectUser) {
            return count(array_intersect($projectUser->permissions, [ProjectPermission::ADMIN, ProjectPermission::WRITE])) > 0;
        }

        if (null === $project->organization) {
            return false;
        }

        $organizationUser = $this->organizationUserRepository->findOneByUserAndOrganization($user, $project->organization);

        if (null === $organizationUser) {
            return false;
        }

        return count(array_intersect($organizationUser->permissions, [OrganizationPermission::ADMIN, OrganizationPermission::PROJECT_CREATE, OrganizationPermission::PROJECT_WRITE])) > 0;
    }
}
