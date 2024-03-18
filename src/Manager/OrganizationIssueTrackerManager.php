<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Issue;
use App\Gateway\IssueTrackerGatewayFactory;
use App\Repository\OrganizationIssueTrackerConfigurationRepository;
use App\Repository\ProjectRepository;

readonly class OrganizationIssueTrackerManager
{
    public function __construct(
        private OrganizationIssueTrackerConfigurationRepository $organizationIssueTrackerConfigurationRepository,
        private ProjectRepository $projectRepository,
        private IssueTrackerGatewayFactory $trackerGatewayFactory
    ) {}

    /**
     * @throws \App\Exception\IssueSynchronizationFailedException
     * @throws \App\Exception\IssueTrackerConfigurationNotFoundException
     * @throws \App\Exception\ProjectNotFoundException
     */
    public function sendToTracker(Issue $issue): void
    {
        $project = $this->projectRepository->findFeatureRootProject($issue->feature);

        if (!$project->organization) {
            return;
        }

        $configuration = $this
            ->organizationIssueTrackerConfigurationRepository
            ->findOneByOrganizationAndTracker($project->organization, $issue->issueTracker);

        $this->trackerGatewayFactory->buildIssueTrackerGateway($configuration)->sync($issue);
    }
}
