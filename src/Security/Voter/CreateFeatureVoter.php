<?php

namespace App\Security\Voter;

use App\Entity\Feature;
use App\Exception\ProjectNotFoundException;
use App\Repository\FeatureRepository;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateFeatureVoter extends Voter
{
    use WriteProjectVoterTrait;

    private FeatureRepository $featureRepository;

    private ProjectRepository $projectRepository;

    public function __construct(
        FeatureRepository $featureRepository,
        ProjectRepository $projectRepository,
        ProjectUserRepository $projectUserRepository,
        OrganizationUserRepository $organizationUserRepository
    ) {
        $this->featureRepository = $featureRepository;
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::CREATE && $subject instanceof Feature;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        try {
            if (null !== $subject->id && null !== $this->featureRepository->find($subject->id)) {
                return false;
            }

            return $this->isAllowedToWriteProject($token, $this->projectRepository->findPathRootProject($subject->path));
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
