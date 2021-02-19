<?php

namespace App\Security\Voter;

use App\Entity\ProjectUser;
use App\Entity\User;
use App\Security\ProjectPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateProjectUserTokenVoter extends Voter
{
    protected function supports(string $attribute, $subject)
    {
        return $attribute === Verb::CREATE_TOKEN && $subject instanceof ProjectUser;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return in_array(ProjectPermission::PULL, $subject->permissions, true);
    }
}
