<?php

namespace App\Helper;

class ExtractSerializationGroupHelper
{
    public static function extractGroup(array $context): array
    {
        return $context['groups'] ?? [];
    }
}
