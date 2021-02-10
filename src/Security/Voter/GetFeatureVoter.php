<?php

namespace App\Security\Voter;

use App\Entity\Feature;
use App\Exception\ProjectNotFoundException;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GetFeatureVoter extends Voter
{
    use ReadProjectVoterTrait;

    private ProjectRepository $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        OrganizationUserRepository $organizationUserRepository,
        ProjectUserRepository $projectUserRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->organizationUserRepository = $organizationUserRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::READ && $subject instanceof Feature;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        try {
            return $this->isAllowedToReadProject($token, $this->projectRepository->findFeatureRootProject($subject));
        } catch (ProjectNotFoundException $e) {
            return false;
        }
    }
}
