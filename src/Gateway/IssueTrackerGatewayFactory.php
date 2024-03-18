<?php

declare(strict_types=1);

namespace App\Gateway;

use App\Entity\OrganizationIssueTrackerConfiguration;

interface IssueTrackerGatewayFactory
{
    function buildIssueTrackerGateway(OrganizationIssueTrackerConfiguration $configuration): IssueTrackerGateway;
}
