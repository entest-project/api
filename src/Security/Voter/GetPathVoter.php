<?php

namespace App\Security\Voter;

use App\Entity\Path;
use App\Exception\ProjectNotFoundException;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GetPathVoter extends Voter
{
    use ReadProjectVoterTrait;

    public function __construct(
        private readonly ProjectRepository $projectRepository,
        OrganizationUserRepository $organizationUserRepository,
        ProjectUserRepository $projectUserRepository
    ) {
        $this->organizationUserRepository = $organizationUserRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Verb::READ && $subject instanceof Path;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        try {
            return $this->isAllowedToReadProject($token, $this->projectRepository->findPathRootProject($subject));
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
