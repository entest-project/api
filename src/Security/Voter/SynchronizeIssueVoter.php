<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Issue;
use App\Exception\ProjectNotFoundException;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SynchronizeIssueVoter extends Voter
{
    use WriteProjectVoterTrait;

    public function __construct(
        private readonly ProjectRepository $projectRepository,
        ProjectUserRepository $projectUserRepository,
        OrganizationUserRepository $organizationUserRepository
    ) {
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::SYNC && $subject instanceof Issue;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        try {
            $project = $this->projectRepository->findPathRootProject($subject->feature->path);
        } catch (ProjectNotFoundException) {
            return false;
        }

        return $this->isAllowedToWriteProject($token, $project);
    }
}
