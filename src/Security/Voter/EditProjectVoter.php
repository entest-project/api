<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditProjectVoter extends Voter
{
    use WriteProjectVoterTrait;

    public function __construct(ProjectUserRepository $projectUserRepository, OrganizationUserRepository $organizationUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::UPDATE && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->isAllowedToWriteProject($token, $subject);
    }
}
