<?php

declare(strict_types=1);

namespace App\Gateway\Impl;

use App\Entity\IssueTracker;
use App\Entity\OrganizationIssueTrackerConfiguration;
use App\Gateway\IssueTrackerGateway;
use App\Gateway\IssueTrackerGatewayFactory;
use App\Transformer\FeatureToJiraCommentTransformer;

readonly class IssueTrackerGatewayFactoryImpl implements IssueTrackerGatewayFactory
{
    public function __construct(private FeatureToJiraCommentTransformer $featureToJiraCommentTransformer)
    {
    }

    public function buildIssueTrackerGateway(OrganizationIssueTrackerConfiguration $configuration): IssueTrackerGateway
    {
        return match ($configuration->issueTracker) {
            IssueTracker::Jira => new JiraIssueTrackerClient(
                $this->featureToJiraCommentTransformer,
                $configuration->apiUrl,
                $configuration->accessToken,
                $configuration->userIdentifier
            )
        };
    }
}
