<?php

namespace App\Security\Voter;

use App\Entity\OrganizationUser;
use App\Repository\OrganizationUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateOrganizationUserVoter extends Voter
{
    use AdminOrganizationVoterTrait;

    public function __construct(OrganizationUserRepository $organizationUserRepository)
    {
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::CREATE && $subject instanceof OrganizationUser;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->isAllowedToAdministrateOrganization($token, $subject->organization);
    }
}
