<?php

namespace App\Model\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestModel
{
    public static function fromRequest(Request $request): self;
}
