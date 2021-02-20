<?php

namespace App\Security\TokenExtractor;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor as BaseExtractor;

class AuthorizationHeaderTokenExtractor extends BaseExtractor
{
    const NAME = 'Authorization';
    const PREFIX = 'Pull';

    public function __construct()
    {
        parent::__construct(self::PREFIX, self::NAME);
    }
}
