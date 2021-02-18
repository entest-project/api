<?php

namespace App\Security\Voter;

use App\Entity\OrganizationUser;
use App\Entity\User;
use App\Repository\OrganizationUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteOrganizationUserVoter extends Voter
{
    use AdminOrganizationVoterTrait;

    public function __construct(OrganizationUserRepository $organizationUserRepository)
    {
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::DELETE && $subject instanceof OrganizationUser;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($subject->user->id === $user->id) {
            return true;
        }

        return $this->isAllowedToAdministrateOrganization($token, $subject->organization);
    }
}
