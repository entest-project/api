<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class InvalidTokenException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'Invalid Pull Token';
    }
}
