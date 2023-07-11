<?php

namespace App\Security\Voter;

use App\Entity\Path;
use App\Exception\ProjectNotFoundException;
use App\Helper\UuidHelper;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditPathVoter extends Voter
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
        return $attribute === Verb::UPDATE && $subject instanceof Path;
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
            if (null === $subject->id) {
                return false;
            }

            $existingPathProjectId = $this->projectRepository->findPathRootProjectId($subject->id)['id'];
            $newPathProject = $this->projectRepository->findPathRootProject($subject->parent);

            if (UuidHelper::canonicalUuid($newPathProject->id) != UuidHelper::canonicalUuid($existingPathProjectId)) {
                return false;
            }

            return $this->isAllowedToWriteProject($token, $newPathProject);
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
