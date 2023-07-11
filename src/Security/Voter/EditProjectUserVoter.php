<?php

namespace App\Security\Voter;

use App\Entity\ProjectUser;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditProjectUserVoter extends Voter
{
    use AdminProjectVoterTrait;

    public function __construct(ProjectUserRepository $projectUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::UPDATE && $subject instanceof ProjectUser;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->isAllowedToAdministrateProject($token, $subject->project);
    }
}
