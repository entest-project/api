<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{
    public static function extractFromContent(Request $request, string $attribute)
    {
        $content = json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return null;
        }

        return $content[$attribute] ?? null;
    }
}
