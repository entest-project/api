<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectUserRepository;
use App\Security\ProjectPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait AdminProjectVoterTrait
{
    private ProjectUserRepository $projectUserRepository;

    private function isAllowedToAdministrateProject(TokenInterface $token, Project $project): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $projectUser = $this->projectUserRepository->findOneByUserAndProject($user, $project);

        if (null === $projectUser) {
            return false;
        }

        return in_array(ProjectPermission::ADMIN, $projectUser->permissions, true);
    }
}
