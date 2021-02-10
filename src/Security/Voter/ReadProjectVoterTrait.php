<?php

namespace App\Security\Voter;

use App\Entity\Organization;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait ReadProjectVoterTrait
{
    private OrganizationUserRepository $organizationUserRepository;

    private ProjectUserRepository $projectUserRepository;

    private function isAllowedToReadProject(TokenInterface $token, Project $project): bool
    {
        if ($project->visibility === Project::VISIBILITY_PUBLIC) {
            return true;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $projectUser = $this->projectUserRepository->findOneByUserAndProject($user, $project);

        if (null !== $projectUser) {
            return true;
        }

        if ($project->visibility === Project::VISIBILITY_PRIVATE) {
            return false;
        }

        if (!$project->organization instanceof Organization) {
            return false;
        }

        $organizationUser = $this->organizationUserRepository->findOneByUserAndOrganization($user, $project->organization);

        return null !== $organizationUser;
    }
}
