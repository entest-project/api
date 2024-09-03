<?php

namespace App\Helper;

readonly class ExtractSerializationGroupHelper
{
    public static function extractGroup(array $context): array
    {
        return $context['groups'] ?? [];
    }
}
