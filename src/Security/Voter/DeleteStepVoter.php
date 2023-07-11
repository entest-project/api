<?php

namespace App\Security\Voter;

use App\Entity\Step;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteStepVoter extends Voter
{
    use WriteProjectVoterTrait;

    public function __construct(ProjectUserRepository $projectUserRepository, OrganizationUserRepository $organizationUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::DELETE && $subject instanceof Step;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->isAllowedToWriteProject($token, $subject->project);
    }
}
