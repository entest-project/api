<?php

namespace App\Security\Voter;

use App\Entity\Path;
use App\Exception\ProjectNotFoundException;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeletePathVoter extends Voter
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
        return $attribute === Verb::DELETE && $subject instanceof Path;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        try {
            if (null === $subject->parent) {
                return false;
            }

            return $this->isAllowedToWriteProject($token, $this->projectRepository->findPathRootProject($subject));
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
