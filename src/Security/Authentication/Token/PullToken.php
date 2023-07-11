<?php

declare(strict_types=1);

namespace App\Security\Authentication\Token;

use App\Entity\ProjectUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class PullToken extends PostAuthenticationToken
{
    public function __construct(private readonly ProjectUser $projectUser, UserInterface $user, string $firewallName, array $roles)
    {
        parent::__construct($user, $firewallName, $roles);
    }

    public function getProjectUser(): ProjectUser
    {
        return $this->projectUser;
    }
}
