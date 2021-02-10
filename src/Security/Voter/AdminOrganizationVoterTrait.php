<?php

namespace App\Security\Voter;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationUserRepository;
use App\Security\OrganizationPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait AdminOrganizationVoterTrait
{
    private OrganizationUserRepository $organizationUserRepository;

    private function isAllowedToAdministrateOrganization(TokenInterface $token, Organization $organization): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $organizationUser = $this->organizationUserRepository->findOneByUserAndOrganization($user, $organization);

        if (null === $organizationUser) {
            return false;
        }

        return in_array(OrganizationPermission::ADMIN, $organizationUser->permissions, true);
    }
}
