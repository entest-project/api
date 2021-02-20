<?php

namespace App\Security\Authentication\Token;

use App\Entity\ProjectUser;
use Symfony\Component\Security\Guard\Token\PreAuthenticationGuardToken;

class PreAuthenticationPullToken extends PreAuthenticationGuardToken
{
    private string $rawToken;

    private ProjectUser $projectUser;

    public function __construct(string $rawToken)
    {
        $this->rawToken = $rawToken;
    }

    public function getProjectUser(): ProjectUser
    {
        return $this->projectUser;
    }

    public function setProjectUser(ProjectUser $projectUser): void
    {
        $this->projectUser = $projectUser;
    }
}
