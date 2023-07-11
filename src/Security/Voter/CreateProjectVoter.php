<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\OrganizationUserRepository;
use App\Security\OrganizationPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateProjectVoter extends Voter
{
    public function __construct(private readonly OrganizationUserRepository $organizationUserRepository)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::CREATE && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $this->isAllowedToCreateProject($subject, $user);
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
