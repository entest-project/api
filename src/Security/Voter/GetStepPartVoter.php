<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\StepPart;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GetStepPartVoter extends Voter
{
    use WriteProjectVoterTrait;

    public function __construct(ProjectUserRepository $projectUserRepository, OrganizationUserRepository $organizationUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::READ && $subject instanceof StepPart;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->isAllowedToWriteProject($token, $subject->step->project);
    }
}
