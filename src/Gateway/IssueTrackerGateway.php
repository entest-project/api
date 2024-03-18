<?php

declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Feature;
use App\Entity\Issue;

interface IssueTrackerGateway
{
    /**
     * @throws \App\Exception\IssueSynchronizationFailedException
     */
    function sync(Issue $issue): void;
}
