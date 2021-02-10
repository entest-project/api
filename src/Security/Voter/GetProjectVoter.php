<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GetProjectVoter extends Voter
{
    use ReadProjectVoterTrait;

    public function __construct(OrganizationUserRepository $organizationUserRepository, ProjectUserRepository $projectUserRepository)
    {
        $this->organizationUserRepository = $organizationUserRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::READ && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->isAllowedToReadProject($token, $subject);
    }
}
