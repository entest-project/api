<?php

namespace App\Security\Voter;

use App\Entity\Feature;
use App\Exception\ProjectNotFoundException;
use App\Helper\UuidHelper;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditFeatureVoter extends Voter
{
    use WriteProjectVoterTrait;

    private ProjectRepository $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        ProjectUserRepository $projectUserRepository,
        OrganizationUserRepository $organizationUserRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::UPDATE && $subject instanceof Feature;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        try {
            if (null === $subject->id) {
                return false;
            }

            $existingFeatureProjectId = $this->projectRepository->findFeatureRootProjectId($subject->id)['id'];
            $newFeatureProject = $this->projectRepository->findPathRootProject($subject->path);

            if (UuidHelper::canonicalUuid($existingFeatureProjectId) != UuidHelper::canonicalUuid($newFeatureProject->id)) {
                return false;
            }

            return $this->isAllowedToWriteProject($token, $newFeatureProject);
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
