<?php

namespace App\Helper;

use Symfony\Component\Uid\Uuid;

class UuidHelper
{
    /**
     * @param string|Uuid $uuid
     */
    public static function canonicalUuid($uuid): string
    {
        if (is_string($uuid)) {
            return $uuid;
        }

        if ($uuid instanceof Uuid) {
            return $uuid->toRfc4122();
        }

        return '';
    }
}
