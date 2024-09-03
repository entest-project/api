<?php

namespace App\Helper;

use Symfony\Component\Uid\Uuid;

readonly class UuidHelper
{
    public static function canonicalUuid(string|Uuid $uuid): string
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
