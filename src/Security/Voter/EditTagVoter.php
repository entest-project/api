<?php

namespace App\Security\Voter;

use App\Entity\Tag;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditTagVoter extends Voter
{
    use WriteProjectVoterTrait;

    public function __construct(
        ProjectUserRepository $projectUserRepository,
        OrganizationUserRepository $organizationUserRepository
    ) {
        $this->projectUserRepository = $projectUserRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::UPDATE && $subject instanceof Tag;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        if (null === $subject->id) {
            return false;
        }

        return $this->isAllowedToWriteProject($token, $subject->project);
    }
}
