<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditProjectVoter extends Voter
{
    use AdminProjectVoterTrait;

    public function __construct(ProjectUserRepository $projectUserRepository, private  readonly OrganizationUserRepository $organizationUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::UPDATE && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->isAllowedToAdministrateProject($token, $subject);
    }
}
