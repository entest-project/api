<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidTokenException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Invalid Pull Token';
    }
}
