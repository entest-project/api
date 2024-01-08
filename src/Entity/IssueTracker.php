<?php

declare(strict_types=1);

namespace App\Entity;

enum IssueTracker: string
{
    case Jira = 'jira';
}
