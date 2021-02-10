<?php

namespace App\Helper;

use JMS\Serializer\Context;

class ExtractSerializationGroupHelper
{
    public static function extractGroup(Context $context): array
    {
        return $context->hasAttribute('groups') ? $context->getAttribute('groups') : [];
    }
}
