<?php

namespace App\Security\Voter;

use App\Entity\Path;
use App\Exception\ProjectNotFoundException;
use App\Repository\OrganizationUserRepository;
use App\Repository\PathRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreatePathVoter extends Voter
{
    use WriteProjectVoterTrait;

    private PathRepository $pathRepository;

    private ProjectRepository $projectRepository;

    public function __construct(
        PathRepository $pathRepository,
        ProjectRepository $projectRepository,
        ProjectUserRepository $projectUserRepository,
        OrganizationUserRepository $organizationUserRepository
    ) {
        $this->pathRepository = $pathRepository;
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::CREATE && $subject instanceof Path;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        try {
            if (null === $subject->parent) {
                return false;
            }
            if (null !== $subject->id && null !== $this->pathRepository->find($subject->id)) {
                return false;
            }

            return $this->isAllowedToWriteProject($token, $this->projectRepository->findPathRootProject($subject->parent));
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
